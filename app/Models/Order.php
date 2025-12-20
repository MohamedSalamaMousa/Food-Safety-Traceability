<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'status',
        'served_at',
        'notes',
    ];

    protected $casts = [
        'status' => 'string',
        'served_at' => 'datetime',
    ];

    /**
     * Get all cooking logs associated with this order
     */
    public function cookingLogs(): HasMany
    {
        return $this->hasMany(CookingLog::class);
    }

    /**
     * Get all order items for this order
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all complaints related to this order
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}
