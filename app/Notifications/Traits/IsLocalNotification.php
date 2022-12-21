<?php

namespace App\Notifications\Traits;

use Illuminate\Notifications\Messages\BroadcastMessage;

trait IsLocalNotification
{
    public function via(mixed $notifiable): array
    {
        return ["database", "broadcast"];
    }

    public function toDatabase(mixed $notifiable): array
    {
        return $this->data();
    }

    public function toBroadcast(mixed $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            "data" => $this->data(),
            "presentation" => static::presentation($this->data())
        ]);
    }

    abstract public function data(): array;

    abstract public static function presentation(array $data): array;
}
