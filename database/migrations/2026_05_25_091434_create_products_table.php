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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('specifications')->nullable(); // stored as JSON string
            $table->decimal('price_per_day', 12, 2);
            $table->decimal('rating', 3, 2)->default(5.0);
            $table->string('status')->default('available'); // available | unavailable | maintenance
            $table->string('thumbnail')->nullable();
            $table->text('gallery')->nullable(); // stored as JSON string of image paths
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
