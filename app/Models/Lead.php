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

    /**
     * Relationship with the User assigned to manage this lead
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Relationship with the User who created this lead record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Accessor for full name: $lead->full_name
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for user's leads (Sales context)
     */
    public function scopeMyLeads($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('assigned_to', $userId)
              ->orWhere('created_by', $userId);
        });
    }


     /* Links the Lead to a registered User account by email
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}