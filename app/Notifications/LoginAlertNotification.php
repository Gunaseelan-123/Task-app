<?php

namespace App\Notifications;

use App\Models\LoginAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginAlertNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly LoginAlert $alert)
    {
    }

    public function via(object $notifiable): array
    {
        $mailDriver = config('mail.default');

        if ($mailDriver === 'log' || $mailDriver === 'array') {
            return ['mail'];
        }

        if (empty(config('mail.mailers.smtp.host')) && $mailDriver === 'smtp') {
            return ['database'];
        }

        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('New login detected')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('A new sign-in was detected on your account.')
            ->line('Time: '.$this->alert->logged_in_at?->format('d M Y H:i'))
            ->line('IP: '.($this->alert->ip_address ?: 'Unavailable'))
            ->line('Device: '.str($this->alert->user_agent ?: 'Unknown browser')->limit(120))
            ->line('If this was not you, please reset your password and review active sessions immediately.');
    }
}
