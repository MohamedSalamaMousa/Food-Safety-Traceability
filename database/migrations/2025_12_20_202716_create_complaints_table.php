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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('restrict');
            $table->foreignId('processing_batch_id')->nullable()->constrained('processing_batches')->onDelete('set null');
            $table->string('complaint_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('symptoms');
            $table->text('incident_description');
            $table->timestamp('incident_date');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['reported', 'investigating', 'resolved', 'closed'])->default('reported');
            $table->text('investigation_notes')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('processing_batch_id');
            $table->index('complaint_number');
            $table->index('incident_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
