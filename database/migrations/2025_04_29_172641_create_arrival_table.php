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
        Schema::create('lates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("employee_id");
            $table->date("date");
            $table->time("arrival_time");
            $table->time("departure_time");
            $table->boolean("late");
            $table->timestamps();

            $table->foreign(columns: 'employee_id')->references('id')->on('employees');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lates');
    }
};
