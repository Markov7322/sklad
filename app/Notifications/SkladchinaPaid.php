<?php

namespace App\Notifications;

use App\Models\Skladchina;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SkladchinaPaid extends Notification
{
    use Queueable;

    public function __construct(public Skladchina $skladchina, public User $user)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->user->name} оплатил складчину {$this->skladchina->name}",
        ];
    }
}
