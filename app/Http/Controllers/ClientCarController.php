<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientCarController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'client') {
            abort(403, 'Само клиенти имат достъп до този екран.');
        }

        $cars = Auth::user()->cars;
        return view('my_cars.index', compact('cars'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'client') {
            abort(403);
        }

        return view('my_cars.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'client') {
            abort(403);
        }

        $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:cars,plate_number',
            'vin' => 'nullable|string|max:255|unique:cars,vin',
        ]);

        Auth::user()->cars()->create([
            'make' => $request->make,
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'vin' => $request->vin,
            'client_id' => null,
        ]);

        return redirect()->route('my_cars.index')->with('success', 'Автомобилът е добавен успешно във вашия гараж!');
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        
        if (Auth::user()->role !== 'client' || $car->user_id !== Auth::id()) {
            abort(403);
        }

        // Защита: Ако колата вече има история на ремонти при нас, не позволяваме на клиента да я изтрие
        if ($car->repairs()->count() > 0) {
            return redirect()->route('my_cars.index')->with('success', 'Внимание: Този автомобил има история със сервиза и не може да бъде изтрит! Свържете се със служител.');
        }

        $car->delete();
        return redirect()->route('my_cars.index')->with('success', 'Автомобилът е премахнат успешно!');
    }
}
