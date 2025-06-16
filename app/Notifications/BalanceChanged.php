<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class BalanceChanged extends Notification
{
    use Queueable;

    public function __construct(public string $description, public float $amount)
    {
    }

    public function via(object $notifiable): array
    {
        return $notifiable->notify_balance_changes ? [WebPushChannel::class] : [];
    }

    public function toWebPush(object $notifiable, object $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Движение баланса')
            ->icon('/icons/icon-192x192.png')
            ->body($this->description.' ('.number_format($this->amount,2,'',' ').' ₽)')
            ->data(['url' => route('account.balance')]);
    }
}
