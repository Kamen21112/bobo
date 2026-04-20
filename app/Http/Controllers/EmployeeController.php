<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Добавяме това за по-добра работа с текущия потребител

class EmployeeController extends Controller
{
    public function index()
    {
        // Проверяваме дали потребителят е админ
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Нямате права за достъп до тази страница.');
        }

        $employees = User::all();
        
        return view('employees.index', compact('employees'));
    }

    public function edit(User $employee)
    {
        // Проверяваме дали потребителят е админ
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Нямате права за достъп до тази страница.');
        }

        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        // Проверяваме дали потребителят е админ
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Нямате права за достъп до тази страница.');
        }

        // Проверяваме дали данните от формата са валидни
        $request->validate([
            'role' => 'required|in:admin,mechanic,client',
            'salary' => 'nullable|numeric|min:0',
        ]);

        // Обновяваме данните на служителя
        $employee->update([
            'role' => $request->role,
            'salary' => $request->salary,
        ]);

        // Връщаме се към списъка със съобщение за успех
        return redirect()->route('employees.index')->with('success', 'Данните на служителя са обновени успешно!');
    }
}