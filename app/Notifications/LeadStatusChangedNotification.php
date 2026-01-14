<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeadStatusChangedNotification extends Notification
{
    use Queueable;

    private Lead $lead;
    private string $oldStatus;
    private string $newStatus;

    public function __construct(Lead $lead, string $oldStatus, string $newStatus)
    {
        $this->lead = $lead;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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
            'type' => 'lead_status_changed',
            'title' => 'Lead Status Updated',
            'message' => "Status Update: Lead {$this->lead->full_name}'s status changed to {$this->newStatus}",
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->full_name,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'icon' => 'refresh',
            'action_url' => route('leads.show', $this->lead->id),
        ];
    }
}
