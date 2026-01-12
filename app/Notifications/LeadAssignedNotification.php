<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeadAssignedNotification extends Notification
{
    use Queueable;

    private Lead $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Specify notification channels
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Database representation of the notification
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'lead_assigned',
            'title' => 'New Lead Assigned',
            'message' => "New Lead: You have been assigned to lead {$this->lead->full_name}",
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->full_name,
            'icon' => 'user-plus',
            'action_url' => route('leads.show', $this->lead->id),
        ];
    }
}
