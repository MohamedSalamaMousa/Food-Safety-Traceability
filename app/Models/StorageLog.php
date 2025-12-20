<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageLog extends Model
{
    protected $fillable = [
        'raw_meat_batch_id',
        'temperature_celsius',
        'humidity_percentage',
        'notes',
        'logged_at',
    ];

    protected $casts = [
        'temperature_celsius' => 'decimal:2',
        'humidity_percentage' => 'decimal:2',
        'logged_at' => 'datetime',
    ];

    /**
     * Get the raw meat batch this storage log belongs to
     */
    public function rawMeatBatch(): BelongsTo
    {
        return $this->belongsTo(RawMeatBatch::class);
    }
}
