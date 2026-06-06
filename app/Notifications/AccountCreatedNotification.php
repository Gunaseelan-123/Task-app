<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreatedNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Your account has been created')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your account has been successfully created.')
            ->line('You can now log in and start using the site.')
            ->line('If you did not create this account, contact support immediately.');
    }
}
