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
        User::create([
            'name' => 'Главeн Администратор', 
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'), // Hash::make криптира паролата
            'role' => 'admin', // Даваме му админски права
        ]);
    }
}
