<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    private function adminOnly(): void
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Само администраторите имат достъп до Доставчиците.');
        }
    }

    public function index()
    {
        $this->adminOnly();
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        $this->adminOnly();
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $this->adminOnly();

        $validatedData = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
        ]);

        Supplier::create($validatedData);
        return redirect()->route('suppliers.index')->with('success', 'Доставчикът е добавен успешно!');
    }

    public function edit(Supplier $supplier)
    {
        $this->adminOnly();
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $this->adminOnly();

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
        ]);

        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success', 'Доставчикът е обновен!');
    }

    public function destroy(Supplier $supplier)
    {
        $this->adminOnly();
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Доставчикът е премахнат успешно!');
    }
}
