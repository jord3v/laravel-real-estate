<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->json('phones')->nullable();
            $table->json('social')->nullable(); // facebook, instagram, linkedin, youtube
            $table->json('address')->nullable();
            $table->json('business_hours')->nullable();
            $table->string('theme')->nullable();
            $table->timestamps();
            $table->json('data')->nullable(); // pode remover se não quiser mais usar o campo genérico
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
