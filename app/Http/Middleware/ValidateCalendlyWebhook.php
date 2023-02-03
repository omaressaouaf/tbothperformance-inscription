<?php

namespace App\Http\Middleware;

use Closure;
use App\Calendly\Calendly;
use Illuminate\Http\Request;

class ValidateCalendlyWebhook
{
    public function handle(Request $request, Closure $next)
    {
        $calendly = app(Calendly::class);

        if (!$calendly->webhookIsValid($request)) return abort(401);

        return $next($request);
    }
}
