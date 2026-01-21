<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * We include 'message' here to solve the SQL 1364 error.
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message', 
        'is_read'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Relationship to the user who sent the message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relationship to the user who received the message
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}