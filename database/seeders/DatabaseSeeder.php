<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Създаваме главния Админски акаунт
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Главeн Администратор', 'password' => Hash::make('admin123'), 'role' => 'admin']
        );

        // Създаваме тестов Механик
        $mechanic = User::firstOrCreate(
            ['email' => 'mechanic@shop.com'],
            ['name' => 'Иван Механика', 'password' => Hash::make('mechanic123'), 'role' => 'mechanic', 'salary' => 1500]
        );

        // Създаваме тестов онлайн Клиент
        $clientUser = User::firstOrCreate(
            ['email' => 'client@gmail.com'],
            ['name' => 'Петър Клиентов', 'password' => Hash::make('client123'), 'role' => 'client']
        );

        // Създаваме Доставчик
        $supplier = \App\Models\Supplier::firstOrCreate(
            ['phone' => '0888123456'],
            ['name' => 'AutoParts Ltd', 'contact_person' => 'Георги']
        );

        // Създаваме Част в склада
        \App\Models\Part::firstOrCreate(
            ['part_number' => 'ENG-001'],
            ['name' => 'Маслен филтър', 'quantity' => 10, 'price' => 25.50, 'supplier_id' => $supplier->id]
        );

        // Създаваме автомобил за онлайн клиента
        $car = \App\Models\Car::firstOrCreate(
            ['plate_number' => 'СВ1234АВ'],
            ['user_id' => $clientUser->id, 'client_id' => null, 'make' => 'BMW', 'model' => 'X5', 'vin' => 'WBA1234567890']
        );

        // Създаваме визуална заявка
        \App\Models\ServiceRequest::firstOrCreate(
            ['user_id' => $clientUser->id, 'car_id' => $car->id],
            ['description' => 'Тракане при подаване на газ, моля прегледайте ходовата част.', 'status' => 'pending']
        );

        // Създаваме пример за започнат ремонт над тази кола
        \App\Models\Repair::firstOrCreate(
            ['car_id' => $car->id, 'title' => 'Смяна на масло и филтри'],
            ['description' => 'Пълно обслужване на двигател.', 'price' => 150.00, 'status' => 'in_progress', 'mechanic_id' => $mechanic->id]
        );

        // Създаваме тестов Walk-in клиент (без акаунт)
        $walkIn = \App\Models\Client::firstOrCreate(
            ['phone' => '0899999999'],
            ['first_name' => 'Димитър', 'last_name' => 'Димитров', 'email' => 'dimitar@abv.bg']
        );

        // Създаваме автомобил на Walk-in клиента
        \App\Models\Car::firstOrCreate(
            ['plate_number' => 'В2222ВВ'],
            ['user_id' => null, 'client_id' => $walkIn->id, 'make' => 'VW', 'model' => 'Golf 4']
        );
    }
}
