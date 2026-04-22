<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Part;
use App\Models\Car;
use App\Models\Client;
use App\Models\Repair;
use App\Models\ServiceRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ──────────────── ПОТРЕБИТЕЛИ ────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Главен Администратор', 'password' => Hash::make('admin123'), 'role' => 'admin']
        );

        $mechanic1 = User::firstOrCreate(
            ['email' => 'mechanic@shop.com'],
            ['name' => 'Иван Механика', 'password' => Hash::make('mechanic123'), 'role' => 'mechanic', 'salary' => 1500]
        );

        $mechanic2 = User::firstOrCreate(
            ['email' => 'mechanic2@shop.com'],
            ['name' => 'Стоян Петров', 'password' => Hash::make('mechanic123'), 'role' => 'mechanic', 'salary' => 1600]
        );

        $clientUser = User::firstOrCreate(
            ['email' => 'client@gmail.com'],
            ['name' => 'Петър Клиентов', 'password' => Hash::make('client123'), 'role' => 'client']
        );

        // ──────────────── ДОСТАВЧИЦИ ────────────────
        $sup1 = Supplier::firstOrCreate(
            ['phone' => '0888123456'],
            ['name' => 'AutoParts Ltd', 'contact_person' => 'Георги Иванов']
        );

        $sup2 = Supplier::firstOrCreate(
            ['phone' => '0877234567'],
            ['name' => 'МоторТрейд ЕООД', 'contact_person' => 'Николай Стоянов']
        );

        $sup3 = Supplier::firstOrCreate(
            ['phone' => '0899345678'],
            ['name' => 'AutoTechnik BG', 'contact_person' => 'Красимира Петрова']
        );

        $sup4 = Supplier::firstOrCreate(
            ['phone' => '0866456789'],
            ['name' => 'Euro Car Parts', 'contact_person' => 'Мартин Василев']
        );

        $sup5 = Supplier::firstOrCreate(
            ['phone' => '0855567890'],
            ['name' => 'ProAuto Supply', 'contact_person' => 'Елена Димитрова']
        );

        // ──────────────── ЧАСТИ ────────────────
        $parts = [
            ['name' => 'Маслен филтър',             'part_number' => 'ENG-001', 'quantity' => 15, 'price' => 12.50,  'supplier_id' => $sup1->id],
            ['name' => 'Въздушен филтър',            'part_number' => 'ENG-002', 'quantity' => 10, 'price' => 18.00,  'supplier_id' => $sup1->id],
            ['name' => 'Горивен филтър',             'part_number' => 'ENG-003', 'quantity' => 8,  'price' => 22.00,  'supplier_id' => $sup1->id],
            ['name' => 'Поленов филтър (салон)',     'part_number' => 'ENG-004', 'quantity' => 12, 'price' => 15.00,  'supplier_id' => $sup1->id],
            ['name' => 'Спирачни накладки предни',   'part_number' => 'BRK-001', 'quantity' => 20, 'price' => 55.00,  'supplier_id' => $sup2->id],
            ['name' => 'Спирачни накладки задни',    'part_number' => 'BRK-002', 'quantity' => 16, 'price' => 48.00,  'supplier_id' => $sup2->id],
            ['name' => 'Спирачен диск преден',       'part_number' => 'BRK-003', 'quantity' => 6,  'price' => 95.00,  'supplier_id' => $sup2->id],
            ['name' => 'Спирачен диск заден',        'part_number' => 'BRK-004', 'quantity' => 6,  'price' => 85.00,  'supplier_id' => $sup2->id],
            ['name' => 'Спирачен маркуч',            'part_number' => 'BRK-005', 'quantity' => 8,  'price' => 28.00,  'supplier_id' => $sup2->id],
            ['name' => 'Моторно масло 5W-40 (4л)',   'part_number' => 'OIL-001', 'quantity' => 25, 'price' => 42.00,  'supplier_id' => $sup3->id],
            ['name' => 'Трансмисионно масло (1л)',   'part_number' => 'OIL-002', 'quantity' => 14, 'price' => 18.50,  'supplier_id' => $sup3->id],
            ['name' => 'Антифриз (1л)',              'part_number' => 'OIL-003', 'quantity' => 20, 'price' => 8.00,   'supplier_id' => $sup3->id],
            ['name' => 'Спирачна течност DOT4',      'part_number' => 'OIL-004', 'quantity' => 10, 'price' => 9.50,   'supplier_id' => $sup3->id],
            ['name' => 'Амортисьор преден (L)',      'part_number' => 'SUS-001', 'quantity' => 4,  'price' => 145.00, 'supplier_id' => $sup4->id],
            ['name' => 'Амортисьор преден (R)',      'part_number' => 'SUS-002', 'quantity' => 4,  'price' => 145.00, 'supplier_id' => $sup4->id],
            ['name' => 'Амортисьор заден',           'part_number' => 'SUS-003', 'quantity' => 4,  'price' => 120.00, 'supplier_id' => $sup4->id],
            ['name' => 'Главина с лагер предна',     'part_number' => 'SUS-004', 'quantity' => 3,  'price' => 165.00, 'supplier_id' => $sup4->id],
            ['name' => 'Кормилна рейка',             'part_number' => 'SUS-005', 'quantity' => 2,  'price' => 320.00, 'supplier_id' => $sup4->id],
            ['name' => 'Стабилизираща щанга',        'part_number' => 'SUS-006', 'quantity' => 6,  'price' => 38.00,  'supplier_id' => $sup4->id],
            ['name' => 'Каре (шарнир) преден',       'part_number' => 'SUS-007', 'quantity' => 5,  'price' => 72.00,  'supplier_id' => $sup4->id],
            ['name' => 'Свещи комплект (4 бр.)',     'part_number' => 'ELC-001', 'quantity' => 10, 'price' => 35.00,  'supplier_id' => $sup5->id],
            ['name' => 'Акумулатор 60Ah',            'part_number' => 'ELC-002', 'quantity' => 5,  'price' => 185.00, 'supplier_id' => $sup5->id],
            ['name' => 'Генератор',                  'part_number' => 'ELC-003', 'quantity' => 2,  'price' => 280.00, 'supplier_id' => $sup5->id],
            ['name' => 'Стартер',                    'part_number' => 'ELC-004', 'quantity' => 2,  'price' => 240.00, 'supplier_id' => $sup5->id],
            ['name' => 'Лямбда сонда',               'part_number' => 'ELC-005', 'quantity' => 4,  'price' => 95.00,  'supplier_id' => $sup5->id],
            ['name' => 'Катушка за запалване',       'part_number' => 'ELC-006', 'quantity' => 6,  'price' => 55.00,  'supplier_id' => $sup5->id],
            ['name' => 'Воден радиатор',             'part_number' => 'CLG-001', 'quantity' => 3,  'price' => 195.00, 'supplier_id' => $sup1->id],
            ['name' => 'Термостат',                  'part_number' => 'CLG-002', 'quantity' => 8,  'price' => 22.00,  'supplier_id' => $sup1->id],
            ['name' => 'Водна помпа',                'part_number' => 'CLG-003', 'quantity' => 4,  'price' => 68.00,  'supplier_id' => $sup1->id],
            ['name' => 'Вентилатор радиатор',        'part_number' => 'CLG-004', 'quantity' => 3,  'price' => 115.00, 'supplier_id' => $sup2->id],
            ['name' => 'Ангренажен ремък',           'part_number' => 'BLT-001', 'quantity' => 5,  'price' => 45.00,  'supplier_id' => $sup3->id],
            ['name' => 'Пистов ремък',               'part_number' => 'BLT-002', 'quantity' => 8,  'price' => 28.00,  'supplier_id' => $sup3->id],
            ['name' => 'Ремък серво',                'part_number' => 'BLT-003', 'quantity' => 6,  'price' => 24.00,  'supplier_id' => $sup3->id],
        ];

        foreach ($parts as $partData) {
            Part::firstOrCreate(['part_number' => $partData['part_number']], $partData);
        }

        // ──────────────── АВТОМОБИЛИ И КЛИЕНТИ ────────────────
        $car1 = Car::firstOrCreate(
            ['plate_number' => 'СВ1234АВ'],
            ['user_id' => $clientUser->id, 'client_id' => null, 'make' => 'BMW', 'model' => 'X5', 'vin' => 'WBA1234567890']
        );

        $walkIn1 = Client::firstOrCreate(
            ['phone' => '0899999999'],
            ['first_name' => 'Димитър', 'last_name' => 'Димитров', 'email' => 'dimitar@abv.bg']
        );
        $car2 = Car::firstOrCreate(
            ['plate_number' => 'В2222ВВ'],
            ['user_id' => null, 'client_id' => $walkIn1->id, 'make' => 'VW', 'model' => 'Golf', 'vin' => null]
        );

        $walkIn2 = Client::firstOrCreate(
            ['phone' => '0877111222'],
            ['first_name' => 'Мария', 'last_name' => 'Колева', 'email' => 'maria@gmail.com']
        );
        $car3 = Car::firstOrCreate(
            ['plate_number' => 'СА5555КА'],
            ['user_id' => null, 'client_id' => $walkIn2->id, 'make' => 'Toyota', 'model' => 'Corolla', 'vin' => null]
        );

        $walkIn3 = Client::firstOrCreate(
            ['phone' => '0866333444'],
            ['first_name' => 'Красимир', 'last_name' => 'Иванов', 'email' => null]
        );
        $car4 = Car::firstOrCreate(
            ['plate_number' => 'ПБ7777РА'],
            ['user_id' => null, 'client_id' => $walkIn3->id, 'make' => 'Opel', 'model' => 'Astra', 'vin' => null]
        );

        // ──────────────── ЗАЯВКИ И РЕМОНТИ ────────────────
        ServiceRequest::firstOrCreate(
            ['user_id' => $clientUser->id, 'car_id' => $car1->id],
            ['description' => 'Тракане при подаване на газ, моля прегледайте ходовата.', 'status' => 'pending']
        );

        Repair::firstOrCreate(
            ['car_id' => $car1->id, 'title' => 'Смяна на масло и филтри'],
            ['description' => 'Пълно обслужване на двигател.', 'price' => 150.00, 'status' => 'in_progress',
             'mechanic_id' => $mechanic1->id, 'claimed_at' => now()->subDays(1)]
        );

        Repair::firstOrCreate(
            ['car_id' => $car2->id, 'title' => 'Смяна на спирачни накладки'],
            ['description' => 'Предни и задни накладки.', 'price' => 210.00, 'status' => 'completed',
             'mechanic_id' => $mechanic1->id, 'claimed_at' => now()->subDays(3), 'completed_at' => now()->subDays(2)]
        );

        Repair::firstOrCreate(
            ['car_id' => $car3->id, 'title' => 'Диагностика и ходова'],
            ['description' => 'Проверка на ходова и амортисьори.', 'price' => null, 'status' => 'pending', 'mechanic_id' => null]
        );

        Repair::firstOrCreate(
            ['car_id' => $car4->id, 'title' => 'Смяна на ремъци'],
            ['description' => 'Ангренажен + пистов ремък.', 'price' => 180.00, 'status' => 'in_progress',
             'mechanic_id' => $mechanic2->id, 'claimed_at' => now()]
        );
    }
}
