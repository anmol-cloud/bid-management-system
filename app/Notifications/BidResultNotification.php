<?php

namespace App\Notifications;

use App\Models\Bid;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BidResultNotification extends Notification
{
    use Queueable;

    public function __construct(public Bid $bid)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $statusText = $this->bid->status === 'won' ? 'It has been won! 🎉' : 'The result has been obtained.';

        return [
            'title' => 'Bid update',
            'message' => "Your bid for'{$this->bid->job_title}' has been {$statusText}",
            'bid_id' => $this->bid->id,
        ];
    }
}
