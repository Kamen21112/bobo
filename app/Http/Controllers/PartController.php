<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartController extends Controller
{
    private function adminOnly(): void
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Само администраторите могат да управляват склада.');
        }
    }

    // Показва списъка с всички части в склада (механици и admin)
    public function index()
    {
        if (Auth::user()->role === 'client') {
            abort(403, 'Клиентите нямат достъп до Склада.');
        }
        $parts = Part::with('supplier')->get();
        return view('parts.index', compact('parts'));
    }

    // Само admin
    public function create()
    {
        $this->adminOnly();
        $suppliers = Supplier::all();
        return view('parts.create', compact('suppliers'));
    }

    // Само admin
    public function store(Request $request)
    {
        $this->adminOnly();

        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'part_number' => 'nullable|string|max:100',
            'quantity'    => 'required|integer|min:0',
            'price'       => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        Part::create($validatedData);
        return redirect()->route('parts.index')->with('success', 'Частта е добавена успешно в склада!');
    }

    public function edit(Part $part)
    {
        $this->adminOnly();
        $suppliers = Supplier::all();
        return view('parts.edit', compact('part', 'suppliers'));
    }

    public function update(Request $request, Part $part)
    {
        $this->adminOnly();

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'part_number' => 'nullable|string|max:100',
            'quantity'    => 'required|integer|min:0',
            'price'       => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $part->update($validated);
        return redirect()->route('parts.index')->with('success', 'Данните за частта бяха обновени!');
    }

    public function destroy(Part $part)
    {
        $this->adminOnly();
        $part->delete();
        return redirect()->route('parts.index')->with('success', 'Частта е изтрита успешно!');
    }
}
