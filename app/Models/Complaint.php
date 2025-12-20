<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    protected $fillable = [
        'order_id',
        'processing_batch_id',
        'complaint_number',
        'customer_name',
        'customer_phone',
        'symptoms',
        'incident_description',
        'incident_date',
        'severity',
        'status',
        'investigation_notes',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'severity' => 'string',
        'status' => 'string',
    ];

    /**
     * Get the order this complaint is related to
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the processing batch this complaint is related to
     */
    public function processingBatch(): BelongsTo
    {
        return $this->belongsTo(ProcessingBatch::class);
    }
}
