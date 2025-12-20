<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'license_number',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get all raw meat batches from this supplier
     */
    public function rawMeatBatches(): HasMany
    {
        return $this->hasMany(RawMeatBatch::class);
    }
}
