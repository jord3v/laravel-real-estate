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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lease_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['income', 'expense', 'fee', 'refund', 'commission'])->default('income');
            $table->string('category');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->date('payment_date');
            $table->date('paid_at')->nullable();
            $table->date('reference_date');
            $table->enum('status', ['paid', 'pending', 'overdue', 'partially_overdue', 'canceled', 'dispute', 'recurrent'])->default('pending');
            $table->enum('payment_method', ['pix', 'boleto', 'cartao_credito', 'cartao_debito', 'transferencia', 'dinheiro'])->nullable();
            $table->string('transaction_code')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
