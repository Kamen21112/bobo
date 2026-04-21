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
        \Illuminate\Support\Facades\DB::table('service_requests')->truncate();

        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn(['car_make', 'car_model']);
            $table->foreignId('car_id')->after('user_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->dropColumn('car_id');
            $table->string('car_make');
            $table->string('car_model');
        });
    }
};
