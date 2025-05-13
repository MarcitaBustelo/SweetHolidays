<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("employee_id");
            $table->date("start_date");
            $table->date("end_date");
            $table->unsignedBigInteger("holiday_id");
            $table->string('comment')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();

            $table->foreign(columns: 'employee_id')->references('id')->on('users');
            $table->foreign('holiday_id')->references('id')->on('holidays_types');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
