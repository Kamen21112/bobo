<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
// 1. Add this import at the top!
use Illuminate\Support\Facades\Auth; 

class PartController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                // 2. Use Auth:: instead of auth()->
                if (Auth::check() && Auth::user()->role === 'client') {
                    abort(403, 'Клиентите нямат достъп до Склада и Доставчиците.');
                }
                return $next($request);
            }),
        ];
    }

    // Показва списъка с всички части в склада
    public function index()
    {
        $parts = Part::with('supplier')->get();
        return view('parts.index', compact('parts'));
    }

    // ... (rest of your methods: create, store, edit, update)

    // Показва формата за добавяне на нова част
    public function create()
    {
        // Трябват ни доставчиците за падащото меню
        $suppliers = Supplier::all();
        return view('parts.create', compact('suppliers'));
    }

    // Записва новата част в базата данни
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'part_number' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        Part::create($validatedData);

        return redirect()->route('parts.index')->with('success', 'Частта е добавена успешно в склада!');
    }
    public function edit(Part $part)
    {
        $suppliers = Supplier::all();
        return view('parts.edit', compact('part', 'suppliers'));
    }

    public function update(Request $request, Part $part)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'part_number' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $part->update($validated);
        return redirect()->route('parts.index')->with('success', 'Данните за частта бяха обновени!');
    }
}