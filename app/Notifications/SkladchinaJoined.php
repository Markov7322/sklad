<?php

namespace App\Notifications;

use App\Models\Skladchina;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SkladchinaJoined extends Notification
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
            'message' => "{$this->user->name} записался в складчину {$this->skladchina->name}",
        ];
    }
}
