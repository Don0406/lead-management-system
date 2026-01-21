<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // These fields are "fillable" (can be saved to the database)
    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'total',
    ];

    /**
     * Relationship: An order belongs to a specific User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: An order is for a specific Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}