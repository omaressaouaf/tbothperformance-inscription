<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    protected $appends = ["presentation"];

    protected function presentation(): Attribute
    {
        return Attribute::get(
            fn ($value, $attributes) => $attributes["type"]::presentation(
                json_decode($attributes["data"], true)
            )
        );
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return auth_user()->notifications()->findOrFail($value);
    }
}
