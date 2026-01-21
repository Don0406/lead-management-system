<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- Relationships ---

    public function assignedLeads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function createdLeads(): HasMany
    {
        return $this->hasMany(Lead::class, 'created_by');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // --- Role Helper Methods ---

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Aliases to match navigation shorthand
    public function isManager(): bool
    {
        return $this->role === 'sales_manager';
    }

    public function isRep(): bool
    {
        return $this->role === 'sales_rep';
    }

    public function isClient(): bool
    {
        return in_array($this->role, ['client', 'guest']);
    }

    // --- Logic Helpers ---

    /**
     * Get lead query builder based on user protocol permissions.
     */
    public function getAccessibleLeads()
    {
        if ($this->isAdmin() || $this->isManager()) {
            return Lead::query();
        }
        
        if ($this->isRep()) {
            return Lead::where('assigned_to', $this->id);
        }

        if ($this->isClient()) {
            return Lead::where('email', $this->email);
        }

        return Lead::whereRaw('1 = 0');
    }

    /**
     * Returns the sum of 'value' for all accessible leads.
     */
    public function totalValue(): float
    {
        return (float) $this->getAccessibleLeads()->sum('value');
    }

    /**
     * Count unread messages in the terminal
     */
    public function unreadMessagesCount(): int
    {
        return $this->receivedMessages()->where('is_read', false)->count();
    }
}