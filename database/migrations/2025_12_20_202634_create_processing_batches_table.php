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
        Schema::create('processing_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->foreignId('raw_meat_batch_id')->constrained('raw_meat_batches')->onDelete('restrict');
            $table->date('production_date');
            $table->date('expiration_date');
            $table->integer('quantity_units'); // Number of kofta sticks
            $table->enum('status', ['processing', 'ready', 'cooking', 'sold', 'expired', 'wasted'])->default('processing');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('batch_number');
            $table->index('raw_meat_batch_id');
            $table->index('production_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processing_batches');
    }
};
