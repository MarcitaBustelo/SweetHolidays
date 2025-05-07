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
        Schema::create('festives', function (Blueprint $table) {
            $table->id(); // Campo de id automático
            $table->string('name'); // Nombre del festivo
            $table->date('date'); // Fecha del festivo
            $table->unsignedBigInteger('delegation_id')->nullable(); // Referencia a la delegación, puede ser null
            $table->boolean('national')->default(false); // Si es festivo nacional (true o false)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('festives'); // Elimina la tabla festives si existe
    }
};
