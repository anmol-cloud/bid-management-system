<?php

namespace App\Notifications;

use App\Models\UpworkAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AssignmentNotification extends Notification
{
    use Queueable;

    public function __construct(public UpworkAccount $account)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Naya Upwork ID assign hua hai',
            'message' => "{$this->account->account_name} ({$this->account->upwork_id}) aapko assign kiya gaya hai.",
            'upwork_account_id' => $this->account->id,
        ];
    }
}
