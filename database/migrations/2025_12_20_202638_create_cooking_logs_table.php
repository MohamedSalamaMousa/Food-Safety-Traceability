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
        Schema::create('cooking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processing_batch_id')->constrained('processing_batches')->onDelete('restrict');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->integer('quantity_cooked'); // Number of kofta sticks cooked
            $table->decimal('cooking_temperature_celsius', 5, 2);
            $table->integer('cooking_duration_minutes');
            $table->timestamp('cooked_at');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('processing_batch_id');
            $table->index('order_id');
            $table->index('cooked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooking_logs');
    }
};
