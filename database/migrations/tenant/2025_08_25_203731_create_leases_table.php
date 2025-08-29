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
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('lessor_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('lessee_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('guarantor_id')->nullable()->constrained('customers');
            $table->string('contract_type')->default('Residencial');
            $table->integer('term_months')->default(30);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rent_amount', 10, 2);
            $table->integer('due_day')->default(5);
            $table->string('payment_place');
            $table->string('readjustment_index');
            $table->json('alternative_indexes')->nullable();
            $table->decimal('late_payment_fine_percent', 5, 2)->default(1);
            $table->decimal('late_payment_fine_limit', 5, 2)->default(20);
            $table->decimal('late_payment_interest', 5, 2)->nullable();
            $table->boolean('monetary_correction')->default(false);
            $table->json('additional_charges')->nullable();
            $table->text('use_destination');
            $table->text('delivery_conditions')->nullable();
            $table->json('prohibitions')->nullable();
            $table->text('maintenance_obligations')->nullable();
            $table->boolean('benfeitorias')->default(false);
            $table->string('guarantee_type');
            $table->decimal('attorney_fees_percent', 5, 2)->default(20);
            $table->string('elected_forum');
            $table->integer('via_count')->default(3);
            $table->text('notes')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leases');
    }
};
