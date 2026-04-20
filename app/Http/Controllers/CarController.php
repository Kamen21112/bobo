<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Car;
use App\Models\Client; // 1. ДОБАВИ ТОВА: Import the Client model

class CarController extends Controller
{
   // ...

    public function index()
    {
        // Ако потребителят е клиент, му забраняваме достъпа
        if (Auth::user()->role === 'client') {
            abort(403, 'Клиентите нямат достъп до базата данни с автомобили. Моля, използвайте меню "Заявки".');
        }

        $cars = Car::all();
        return view('cars.index', compact('cars'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 2. Взимаме всички клиенти от базата
        $clients = Client::all();

        // Зареждаме страницата с формата за добавяне и подаваме клиентите
        return view('cars.create', compact('clients'));
    }

    public function store(Request $request)
    {
        // 1. Валидация: Вече изискваме и клиент (client_id)
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:cars,plate_number',
            'vin' => 'nullable|string|max:255|unique:cars,vin',
        ]);

        // 2. Създаване: Записваме колата глобално (Car::create), а не през User
        Car::create([
            'client_id' => $request->client_id,
            'make' => $request->make,
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'vin' => $request->vin,
        ]);

        // 3. Пренасочване
        return redirect()->route('cars.index')->with('success', 'Автомобилът е добавен успешно!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        // ПРОМЯНА: Търсим колата директно, без да ползваме user_id
        $car = Car::findOrFail($id);
        
        // Зареждаме страницата за редакция и ѝ подаваме данните на колата
        return view('cars.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:cars,plate_number,' . $id,
            'vin' => 'nullable|string|max:255|unique:cars,vin,' . $id,
        ]);

        // ПРОМЯНА: Търсим колата директно
        $car = Car::findOrFail($id);

        // Обновяваме данните в базата
        $car->update([
            'make' => $request->make,
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'vin' => $request->vin,
        ]);

        // Връщаме се в списъка
        return redirect()->route('cars.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        // ПРОМЯНА: Търсим колата директно
        $car = Car::findOrFail($id);
        
        // Изтриваме я от базата данни
        $car->delete();

        // Връщаме потребителя обратно в списъка
        return redirect()->route('cars.index');
    }
}
