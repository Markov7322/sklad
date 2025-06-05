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
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Складчина {$this->skladchina->name} переведена в статус {$this->skladchina->status_label}",
        ];
    }
}
