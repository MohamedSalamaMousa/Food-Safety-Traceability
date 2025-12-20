<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcessingBatch extends Model
{
    protected $fillable = [
        'batch_number',
        'raw_meat_batch_id',
        'production_date',
        'expiration_date',
        'quantity_units',
        'status',
        'notes',
    ];

    protected $casts = [
        'production_date' => 'date',
        'expiration_date' => 'date',
        'quantity_units' => 'integer',
        'status' => 'string',
    ];

    /**
     * Get the raw meat batch this processing batch was made from
     */
    public function rawMeatBatch(): BelongsTo
    {
        return $this->belongsTo(RawMeatBatch::class);
    }

    /**
     * Get all cooking logs for this processing batch
     */
    public function cookingLogs(): HasMany
    {
        return $this->hasMany(CookingLog::class);
    }

    /**
     * Get all complaints related to this processing batch
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}
