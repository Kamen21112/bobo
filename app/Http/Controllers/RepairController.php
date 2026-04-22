<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Repair;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairController extends Controller
{
    // Показва ремонти, групирани по дата на приемане
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'client') {
            $userCarIds = $user->cars()->pluck('id');
            $repairs = Repair::with(['car', 'mechanic', 'parts'])
                ->whereIn('car_id', $userCarIds)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->role === 'mechanic') {
            $repairs = Repair::with(['car', 'mechanic', 'parts'])
                ->where(function ($q) use ($user) {
                    $q->where('mechanic_id', $user->id)
                      ->orWhereNull('mechanic_id');
                })
                ->orderBy('claimed_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $repairs = Repair::with(['car', 'mechanic', 'parts'])
                ->orderBy('claimed_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Групираме по дата на приемане (claimed_at) или дата на създаване
        $groupedRepairs = $repairs->groupBy(function ($repair) {
            $date = $repair->claimed_at ?? $repair->created_at;
            return $date->format('d.m.Y');
        });

        return view('repairs.index', compact('repairs', 'groupedRepairs'));
    }

    public function create()
    {
        $cars = Car::all();
        return view('repairs.create', compact('cars'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'car_id'      => 'required|exists:cars,id',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0',
        ]);

        Repair::create($validatedData);
        return redirect()->route('repairs.index')->with('success', 'Ремонтът беше добавен успешно!');
    }

    public function edit(Repair $repair)
    {
        if (Auth::user()->role === 'mechanic' && $repair->mechanic_id !== Auth::id()) {
            abort(403, 'Нямате право да променяте този ремонт.');
        }
        $cars = Car::all();
        return view('repairs.edit', compact('repair', 'cars'));
    }

    public function update(Request $request, Repair $repair)
    {
        if (Auth::user()->role === 'mechanic' && $repair->mechanic_id !== Auth::id()) {
            abort(403, 'Нямате право да променяте този ремонт.');
        }

        $validatedData = $request->validate([
            'car_id'      => 'required|exists:cars,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0',
            'status'      => 'required|in:pending,in_progress,completed',
        ]);

        $repair->update($validatedData);
        return redirect()->route('repairs.index')->with('success', 'Ремонтът беше актуализиран успешно!');
    }

    public function destroy(Repair $repair)
    {
        if (Auth::user()->role === 'mechanic' && $repair->mechanic_id !== Auth::id()) {
            abort(403, 'Нямате право да триете този ремонт.');
        }
        $repair->delete();
        return redirect()->route('repairs.index')->with('success', 'Ремонтът беше изтрит успешно!');
    }

    // Механикът поема ремонта
    public function claim(Repair $repair)
    {
        if (Auth::user()->role !== 'mechanic') {
            abort(403, 'Само механици могат да поемат задачи.');
        }
        if ($repair->mechanic_id !== null && $repair->mechanic_id !== Auth::id()) {
            abort(403, 'Ремонтът вече е поет от друг механик.');
        }

        $repair->update([
            'mechanic_id' => Auth::id(),
            'status'      => 'in_progress',
            'claimed_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Ремонтът е поет успешно!');
    }

    // Показва формата за завършване с избор на части
    public function showComplete(Repair $repair)
    {
        if (Auth::id() !== $repair->mechanic_id && Auth::user()->role !== 'admin') {
            abort(403, 'Не сте зачислен към този ремонт.');
        }
        $parts = Part::where('quantity', '>', 0)->orderBy('name')->get();
        return view('repairs.complete', compact('repair', 'parts'));
    }

    // Завършва ремонта и намалява наличностите
    public function complete(Request $request, Repair $repair)
    {
        if (Auth::id() !== $repair->mechanic_id && Auth::user()->role !== 'admin') {
            abort(403, 'Не сте зачислен към този ремонт.');
        }

        // Само POST с parts данни идва от формата; обикновен PATCH = директно завърши
        if ($request->isMethod('POST') || $request->has('parts')) {
            $validated = $request->validate([
                'parts'            => 'nullable|array',
                'parts.*.part_id'  => 'required_with:parts|exists:parts,id',
                'parts.*.quantity' => 'required_with:parts|integer|min:1',
            ]);

            if (!empty($validated['parts'])) {
                foreach ($validated['parts'] as $usedPart) {
                    $part = Part::findOrFail($usedPart['part_id']);
                    $qty  = (int) $usedPart['quantity'];

                    $part->update(['quantity' => max(0, $part->quantity - $qty)]);
                    $repair->parts()->attach($part->id, ['quantity' => $qty]);
                }
            }
        }

        $repair->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('repairs.index')->with('success', 'Ремонтът е завършен и частите са записани!');
    }
}
