<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawMeatBatch extends Model
{
    protected $fillable = [
        'batch_number',
        'supplier_id',
        'production_date',
        'expiration_date',
        'quantity_kg',
        'status',
        'notes',
    ];

    protected $casts = [
        'production_date' => 'date',
        'expiration_date' => 'date',
        'quantity_kg' => 'decimal:2',
        'status' => 'string',
    ];

    /**
     * Get the supplier that provided this raw meat batch
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get all storage logs for this raw meat batch
     */
    public function storageLogs(): HasMany
    {
        return $this->hasMany(StorageLog::class);
    }

    /**
     * Get all processing batches made from this raw meat batch
     */
    public function processingBatches(): HasMany
    {
        return $this->hasMany(ProcessingBatch::class);
    }
}
