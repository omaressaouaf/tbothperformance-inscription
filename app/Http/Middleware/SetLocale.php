<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $locale = auth_user()->locale ?? config("app.locale");
            
            session()->put("locale", $locale);

            app()->setLocale($locale);

            return $next($request);
        }

        if (!session()->has('locale')) {
            session()->put('locale', app()->getLocale());
        }

        app()->setLocale(session()->get('locale'));

        return $next($request);
    }
}
