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
        $statusText = $this->bid->status === 'won' ? 'jeet li gayi hai! 🎉' : 'result mil gaya hai.';

        return [
            'title' => 'Bid update',
            'message' => "'{$this->bid->job_title}' wali bid {$statusText}",
            'bid_id' => $this->bid->id,
        ];
    }
}
