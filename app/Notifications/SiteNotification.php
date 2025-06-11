<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SiteNotification extends Notification
{
    use Queueable;

    public function __construct(public string $title, public string $message, public ?string $url = null)
    {
    }

    public function via(object $notifiable): array
    {
        return $notifiable->notify_site ? [WebPushChannel::class] : [];
    }

    public function toWebPush(object $notifiable, object $notification): WebPushMessage
    {
        return WebPushMessage::create()
            ->title($this->title)
            ->icon('/icons/icon-192x192.png')
            ->body($this->message)
            ->data(['url' => $this->url ?? url('/')]);
    }
}
