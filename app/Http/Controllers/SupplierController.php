<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
// Imports for Laravel 11 Middleware handling
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller implements HasMiddleware
{
    /**
     * Define the middleware for the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                // Using Auth facade to keep the IDE happy and avoid "Undefined method" errors
                if (Auth::check() && Auth::user()->role === 'client') {
                    abort(403, 'Клиентите нямат достъп до Склада и Доставчиците.');
                }
                return $next($request);
            }),
        ];
    }

    // Показва списъка с доставчици
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    // Показва формата за добавяне
    public function create()
    {
        return view('suppliers.create');
    }

    // Записва новия доставчик в базата
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
        ]);

        Supplier::create($validatedData);

        return redirect()->route('suppliers.index')->with('success', 'Доставчикът е добавен успешно!');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
        ]);

        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success', 'Доставчикът е обновен!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Доставчикът е премахнат успешно!');
    }
}