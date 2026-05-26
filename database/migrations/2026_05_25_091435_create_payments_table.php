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
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('payment_type')->default('transfer'); // transfer | midtrans
            $table->string('transaction_id')->nullable(); // midtrans transaction_id or manual ref
            $table->string('status')->default('pending'); // pending | settled | expired | failed
            $table->string('payment_proof')->nullable(); // path for bank transfer proof
            $table->text('raw_payload')->nullable(); // json logs for webhook response
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
