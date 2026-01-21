<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'total',
    ];

    /**
     * The attributes that should be cast.
     * This ensures 'total' is always treated as a number, not a string.
     */
    protected $casts = [
        'total' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * HELPER: Get a formatted Order Number (e.g., #ORD-00042)
     * You can use this in your blade views as {{ $order->formatted_id }}
     */
    public function getFormattedIdAttribute()
    {
        return '#ORD-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Relationship: An order belongs to a specific User (Client).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: An order is for a specific Product (Asset).
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}