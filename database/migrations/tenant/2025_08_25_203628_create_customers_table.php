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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('type');
            $table->string('document_type')->nullable();
            $table->string('cpf')->nullable()->unique();
            $table->string('cnpj')->nullable()->unique();
            $table->string('rg')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('nationality')->nullable();
            $table->string('profession')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('spouse_rg')->nullable();
            $table->string('spouse_cpf')->nullable();
            $table->string('spouse_profession')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
