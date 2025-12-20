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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('cooking_log_id')->constrained('cooking_logs')->onDelete('restrict');
            $table->integer('quantity'); // Number of kofta sticks in this order item
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('cooking_log_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
