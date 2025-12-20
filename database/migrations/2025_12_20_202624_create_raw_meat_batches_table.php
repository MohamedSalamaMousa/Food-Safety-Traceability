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
        Schema::create('raw_meat_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
            $table->date('production_date');
            $table->date('expiration_date');
            $table->decimal('quantity_kg', 10, 2);
            $table->enum('status', ['received', 'in_storage', 'processing', 'expired', 'used'])->default('received');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('batch_number');
            $table->index('supplier_id');
            $table->index('production_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_meat_batches');
    }
};
