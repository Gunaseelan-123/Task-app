<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpCodeNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $plainCode, private readonly string $channel, private readonly string $type = 'verify')
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Your verification code')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your verification code is:')
            ->line($this->plainCode)
            ->line('This code expires in 10 minutes.')
            ->line('If you did not request this, ignore this message.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'code' => $this->plainCode,
            'channel' => $this->channel,
            'type' => $this->type,
        ];
    }
}
