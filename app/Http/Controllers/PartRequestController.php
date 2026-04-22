<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartRequestController extends Controller
{
    /**
     * Списък с всички заявки за части.
     * Механиците виждат само своите, администраторите виждат всички.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = PartRequest::with(['part', 'mechanic']);

        // Механиците виждат само своите заявки
        if ($user->role === 'mechanic') {
            $query->where('mechanic_id', $user->id);
        } elseif ($user->role === 'client') {
            abort(403);
        }

        // Търсене
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('part', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('mechanic', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        // Филтър по статус
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Сортиране – най-нови първо
        $partRequests = $query->orderBy('created_at', 'desc')->get();

        // Експорт
        if ($request->filled('export')) {
            return $this->export($partRequests, $request->export);
        }

        return view('part_requests.index', compact('partRequests'));
    }

    /**
     * Форма за заявяване на нова част (механик избира от наличните).
     */
    public function create()
    {
        if (Auth::user()->role !== 'mechanic' && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $parts = Part::orderBy('name')->get();
        return view('part_requests.create', compact('parts'));
    }

    /**
     * Записва заявката на механика.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'mechanic' && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'part_id'  => 'required|exists:parts,id',
            'quantity' => 'required|integer|min:1',
            'notes'    => 'nullable|string|max:500',
        ]);

        $part = Part::findOrFail($validated['part_id']);

        PartRequest::create([
            'mechanic_id' => Auth::id(),
            'part_id'     => $validated['part_id'],
            'quantity'    => $validated['quantity'],
            'price'       => $part->price,   // initial price from warehouse
            'notes'       => $validated['notes'] ?? null,
            'status'      => 'pending',
        ]);

        return redirect()->route('part_requests.index')->with('success', 'Заявката за частта е изпратена успешно!');
    }

    /**
     * Администраторът обновява статуса и/или цената на заявката.
     */
    public function update(Request $request, PartRequest $partRequest)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Само администраторите могат да управляват заявките за части.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,rejected,ordered,delivered',
            'price'  => 'nullable|numeric|min:0',
            'notes'  => 'nullable|string|max:500',
        ]);

        $oldStatus = $partRequest->status;
        $newStatus = $validated['status'];

        $partRequest->update([
            'status'            => $newStatus,
            'price'             => $validated['price'] ?? $partRequest->price,
            'notes'             => $validated['notes'] ?? $partRequest->notes,
            'status_changed_at' => now(),
        ]);

        // Когато статусът стане "доставена" – увеличаваме наличността в склада
        if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            $partRequest->part->increment('quantity', $partRequest->quantity);
        }

        // Ако се връщаме от "доставена" – намаляваме обратно
        if ($oldStatus === 'delivered' && $newStatus !== 'delivered') {
            $partRequest->part->decrement('quantity', $partRequest->quantity);
        }

        return redirect()->route('part_requests.index')->with('success', 'Заявката е обновена успешно!');
    }

    /**
     * Показва формата за редакция (само за admin).
     */
    public function edit(PartRequest $partRequest)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('part_requests.edit', compact('partRequest'));
    }

    /**
     * Генерира и изтегля Excel файл с заявките.
     */
    private function export($partRequests, string $format = 'xlsx')
    {
        $format = in_array($format, ['xlsx', 'xls']) ? $format : 'xlsx';

        $headers = ['#', 'Механик', 'Част', 'Количество', 'Цена (лв.)', 'Статус', 'Бележки', 'Дата'];
        $rows = [];
        foreach ($partRequests as $pr) {
            $rows[] = [
                $pr->id,
                $pr->mechanic->name ?? '-',
                $pr->part->name ?? '-',
                $pr->quantity,
                number_format($pr->price ?? 0, 2),
                $pr->status_label,
                $pr->notes ?? '-',
                $pr->created_at->format('d.m.Y H:i'),
            ];
        }

        // Build CSV/TSV manually for simplicity (no extra package needed)
        // For xlsx we use a simple PHP approach
        if ($format === 'xls') {
            return $this->buildXls($headers, $rows);
        }

        return $this->buildXlsx($headers, $rows);
    }

    private function buildXlsx(array $headers, array $rows): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // Write a simple XLSX using PHP's ZipArchive + XML
        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new \ZipArchive();
        $zip->open($tmpFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // [Content_Types].xml
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>');

        // _rels/.rels
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>');

        // xl/_rels/workbook.xml.rels
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>');

        // xl/workbook.xml
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"
          xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Заявки" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>');

        // xl/styles.xml (minimal)
        $zip->addFromString('xl/styles.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts><font><sz val="11"/><name val="Calibri"/></font>
         <font><b/><sz val="11"/><name val="Calibri"/></font></fonts>
  <fills><fill><patternFill patternType="none"/></fill>
         <fill><patternFill patternType="gray125"/></fill></fills>
  <borders><border><left/><right/><top/><bottom/><diagonal/></border></borders>
  <cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>
  <cellXfs>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0"/>
  </cellXfs>
</styleSheet>');

        // xl/worksheets/sheet1.xml
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <sheetData>';

        $rowNum = 1;
        // Header row
        $sheetXml .= '<row r="' . $rowNum . '">';
        foreach ($headers as $col => $val) {
            $cellRef = $this->colLetter($col) . $rowNum;
            $escaped = htmlspecialchars((string)$val, ENT_XML1);
            $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr" s="1"><is><t>' . $escaped . '</t></is></c>';
        }
        $sheetXml .= '</row>';
        $rowNum++;

        foreach ($rows as $row) {
            $sheetXml .= '<row r="' . $rowNum . '">';
            foreach ($row as $col => $val) {
                $cellRef = $this->colLetter($col) . $rowNum;
                $escaped = htmlspecialchars((string)$val, ENT_XML1);
                $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr"><is><t>' . $escaped . '</t></is></c>';
            }
            $sheetXml .= '</row>';
            $rowNum++;
        }

        $sheetXml .= '</sheetData></worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->close();

        $content = file_get_contents($tmpFile);
        unlink($tmpFile);

        return response()->streamDownload(function() use ($content) {
            echo $content;
        }, 'zavki_chasti.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function buildXls(array $headers, array $rows): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // Simple HTML-based XLS (Excel opens it fine)
        $html = '<table border="1"><tr>';
        foreach ($headers as $h) {
            $html .= '<th><b>' . htmlspecialchars($h) . '</b></th>';
        }
        $html .= '</tr>';
        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . htmlspecialchars((string)$cell) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        return response()->streamDownload(function() use ($html) {
            echo $html;
        }, 'zavki_chasti.xls', [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    private function colLetter(int $index): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $letters[$index] ?? 'A';
    }
}
