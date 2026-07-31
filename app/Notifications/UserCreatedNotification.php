<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public string $plainPassword)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Account Has Been Created')
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('An account has been created for you. Your login details are below:')
            ->line('Email: ' . $notifiable->email)
            ->line('Password: ' . $this->plainPassword)
            ->action('Login', url('/login'))
            ->line('For security reasons, please change your password after your first login.');
    }
}