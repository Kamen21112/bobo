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
        Schema::table('users', function (Blueprint $table) {
            // Добавяме роля (по подразбиране ще е механик)
            $table->string('role')->default('mechanic')->after('email'); 
        
            // Добавяме заплата (до 999,999.99)
            $table->decimal('salary', 8, 2)->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ако решим да върнем миграцията назад, изтриваме тези колони
            $table->dropColumn(['role', 'salary']);
        });
    }
};
