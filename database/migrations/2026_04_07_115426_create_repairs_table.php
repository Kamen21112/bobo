<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade'); // Връзка с автомобила
            $table->string('title'); // Кратко заглавие на ремонта (напр. "Смяна на масло")
            $table->text('description')->nullable(); // Подробно описание
            $table->decimal('price', 8, 2)->nullable(); // Цена (до 999999.99)
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending'); // Статус на ремонта
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
