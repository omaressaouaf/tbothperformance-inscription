<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SwitchLocaleController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            "locale" => ["required", Rule::in(array_keys(config('app.supported_locales')))]
        ]);

        if (auth()->check() || auth("lead")->check()) {
            $user = auth_user() ?? auth_user("lead");

            $user->locale = $request->locale;

            $user->save();
        } else {
            session()->put('locale', $request->locale);
        }

        return back();
    }
}
