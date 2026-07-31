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
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('A new Upwork job has been assigned.')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line("The account {$this->account->account_name} ({$this->account->upwork_id}) has been assigned to you.")
            ->action('View dashboard', url('/'))
            ->line('Thank you!');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'A new Upwork job has been assigned.',
            'message' => "{$this->account->account_name} ({$this->account->upwork_id}) has to assigned you",
            'upwork_account_id' => $this->account->id,
        ];
    }
}