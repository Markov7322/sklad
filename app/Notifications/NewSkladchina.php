<?php

namespace App\Notifications;

use App\Models\Skladchina;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewSkladchina extends Notification
{
    use Queueable;

    public function __construct(public Skladchina $skladchina)
    {
    }

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->notify_site) {
            $channels[] = WebPushChannel::class;
        }
        return $channels;
    }

    public function toWebPush(object $notifiable, object $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Новая складчина добавлена')
            ->icon('/icons/icon-192x192.png')
            ->body($this->skladchina->title)
            ->action('Открыть', 'open_skladchina')
            ->data(['url' => route('skladchinas.show', $this->skladchina)]);
    }
}
