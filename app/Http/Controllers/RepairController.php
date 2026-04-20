<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Repair;
use App\Models\Car; // We need this to get cars for the creation form
use Illuminate\Http\Request;

class RepairController extends Controller
{
    // 1. Show all repairs
    public function index()
    {
        // Get all repairs and load the associated car data
        $repairs = Repair::with('car')->get(); 
        
        return view('repairs.index', compact('repairs'));
    }

    // 2. Show the form to add a new repair
    public function create()
    {
        // We need all cars so the mechanic can select which car is being repaired
        $cars = Car::all(); 
        
        return view('repairs.create', compact('cars'));
    }

    /**
     * Записва новия ремонт в базата данни.
     */
    public function store(Request $request)
    {
        // 1. Проверяваме дали данните са валидни (сигурност и коректност)
        $validatedData = $request->validate([
            'title' => 'required|string|max:255', // Добавяме задължително заглавие
            'car_id' => 'required|exists:cars,id', // Предполагам, че го имаш нагоре
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        Repair::create($validatedData);

        // 3. Връщаме потребителя към списъка с ремонти СЪС съобщение за успех
        return redirect()->route('repairs.index')->with('success', 'Ремонтът беше добавен успешно!');
    }

    /**
     * Показва формата за редактиране на вече съществуващ ремонт.
     */
    public function edit(Repair $repair)
    {
        // Отново ни трябват колите за падащото меню
        $cars = Car::all(); 
        
        // ВРЪЩАМЕ ИЗГЛЕДА (тук беше грешката, върнах го да отваря формата за редакция)
        return view('repairs.edit', compact('repair', 'cars'));
    }

    /**
     * Обновява данните за ремонта в базата данни.
     */
    public function update(Request $request, Repair $repair)
    {
        // 1. Валидация (тук вече разрешаваме и промяна на статуса)
        $validatedData = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        // 2. Обновяваме конкретния запис
        $repair->update($validatedData);

        // 3. Връщаме потребителя към списъка СЪС съобщение за успех
        return redirect()->route('repairs.index')->with('success', 'Ремонтът беше актуализиран успешно!');
    }

    /**
     * Изтрива ремонта от базата данни.
     */
    public function destroy(Repair $repair)
    {
        // Изтриваме записа
        $repair->delete();

        // Връщаме се към списъка СЪС съобщение за успех
        return redirect()->route('repairs.index')->with('success', 'Ремонтът беше изтрит успешно!');
    }

    public function claim(Repair $repair)
    {
        if (Auth::user()->role !== 'mechanic') {
            abort(403, 'Само механици могат да поемат задачи.');
        }

        $repair->update([
            'mechanic_id' => Auth::id(),
            'status' => 'in_progress'
        ]);

        return redirect()->back()->with('success', 'Ремонтът е поет успешно!');
    }

    // 2. Функция за завършване на ремонт
    public function complete(Repair $repair)
    {
        // Само механикът, който работи по колата, може да я завърши
        if (Auth::id() !== $repair->mechanic_id && Auth::user()->role !== 'admin') {
            abort(403, 'Не сте зачислен към този ремонт.');
        }

        $repair->update([
            'status' => 'completed'
        ]);

        return redirect()->back()->with('success', 'Ремонтът е завършен!');
    }
}