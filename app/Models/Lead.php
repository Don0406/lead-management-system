<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'title',
        'source',
        'status',
        'estimated_value',
        'notes',
        'assigned_to',
        'created_by',
        'contacted_at'
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
        'estimated_value' => 'decimal:2'
    ];

    // Relationship with assigned user
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Relationship with creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Scope for filtering by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for user's leads (for sales reps)
    public function scopeMyLeads($query, $userId)
    {
        return $query->where('assigned_to', $userId)
                    ->orWhere('created_by', $userId);
    }
}