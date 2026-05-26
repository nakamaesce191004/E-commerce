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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->decimal('total_price', 12, 2);
            $table->string('status')->default('pending'); // pending | approved | borrowed | completed | rejected
            $table->text('note')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('phone')->nullable();
            $table->string('payment_method')->default('transfer'); // transfer | midtrans
            $table->string('payment_status')->default('unpaid'); // unpaid | paid | expired
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
