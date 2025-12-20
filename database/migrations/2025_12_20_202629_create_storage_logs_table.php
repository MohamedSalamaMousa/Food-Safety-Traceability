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
        Schema::create('storage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_meat_batch_id')->constrained('raw_meat_batches')->onDelete('cascade');
            $table->decimal('temperature_celsius', 5, 2);
            $table->decimal('humidity_percentage', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('logged_at');
            $table->timestamps();
            
            $table->index('raw_meat_batch_id');
            $table->index('logged_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_logs');
    }
};
