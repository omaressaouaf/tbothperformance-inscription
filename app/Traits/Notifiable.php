<?php

namespace App\Traits;

use App\Models\Notification;
use Illuminate\Notifications\Notifiable as BaseNotifiable;

trait Notifiable
{
    use BaseNotifiable;

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }
}
