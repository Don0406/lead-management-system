<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function assignedLeads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function createdLeads()
    {
        return $this->hasMany(Lead::class, 'created_by');
    }

    // Helper methods for role checking
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSalesManager()
    {
        return $this->role === 'sales_manager';
    }

    public function isSalesRep()
    {
        return $this->role === 'sales_rep';
    }

    // Get leads based on role
    public function getAccessibleLeads()
    {
        if ($this->isAdmin() || $this->isSalesManager()) {
            return Lead::with(['assignedUser', 'creator']);
        }
        
        return Lead::myLeads($this->id)->with(['assignedUser', 'creator']);
    }
}