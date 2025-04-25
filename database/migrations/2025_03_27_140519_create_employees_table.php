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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("company_id");
            $table->unsignedBigInteger("delegation_id");
            $table->string("full_name");
            $table->string("NIF");
            $table->unsignedBigInteger("employee_id");
            $table->string("professional_email");
            $table->unsignedBigInteger("department_id")->nullable(); 
            $table->string("phone");
            $table->date("start_date");
            $table->unsignedBigInteger("responsable_id")->nullable(); 
            $table->integer("days")->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->foreign('delegation_id')->references('delegation_id')->on('delegations');
            $table->foreign('department_id')->references('department_id')->on('departments');
            $table->foreign('responsable_id')->references('responsable_id')->on('responsables');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
