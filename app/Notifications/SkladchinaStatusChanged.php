<?php

namespace App\Notifications;

use App\Models\Skladchina;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SkladchinaStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Skladchina $skladchina)
    {
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if ($notifiable->notify_status_changes) {
            $channels[] = \NotificationChannels\WebPush\WebPushChannel::class;
        }
        return $channels;
    }

    public function toWebPush(object $notifiable, object $notification): \NotificationChannels\WebPush\WebPushMessage
    {
        return \NotificationChannels\WebPush\WebPushMessage::create()
            ->title('Изменен статус складчины')
            ->icon('/icons/icon-192x192.png')
            ->body($this->skladchina->name . ': ' . $this->skladchina->status_label)
            ->data(['url' => route('skladchinas.show', $this->skladchina)]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Складчина {$this->skladchina->name} переведена в статус {$this->skladchina->status_label}",
        ];
    }
}
