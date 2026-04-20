<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Име на частта (напр. Маслен филтър)
            $table->string('part_number')->nullable(); // Сериен номер / SKU
            $table->integer('quantity')->default(0); // Колко бройки имаме в склада
            $table->decimal('price', 8, 2); // Цена
            // Връзка към доставчика:
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
