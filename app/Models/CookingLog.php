<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CookingLog extends Model
{
    protected $fillable = [
        'processing_batch_id',
        'order_id',
        'quantity_cooked',
        'cooking_temperature_celsius',
        'cooking_duration_minutes',
        'cooked_at',
        'notes',
    ];

    protected $casts = [
        'quantity_cooked' => 'integer',
        'cooking_temperature_celsius' => 'decimal:2',
        'cooking_duration_minutes' => 'integer',
        'cooked_at' => 'datetime',
    ];

    /**
     * Get the processing batch this cooking log belongs to
     */
    public function processingBatch(): BelongsTo
    {
        return $this->belongsTo(ProcessingBatch::class);
    }

    /**
     * Get the order this cooking log is associated with
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get all order items that use this cooking log
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
