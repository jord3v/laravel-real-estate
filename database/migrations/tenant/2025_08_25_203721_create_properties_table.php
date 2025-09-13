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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('customers')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('type');
            $table->string('purpose')->nullable();
            $table->json('address')->nullable();
            $table->json('compositions')->nullable();
            $table->json('dimensions')->nullable();
            $table->json('characteristics')->nullable();
            $table->json('business_options')->nullable();
            $table->text('description')->nullable();
            $table->json('publication')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
