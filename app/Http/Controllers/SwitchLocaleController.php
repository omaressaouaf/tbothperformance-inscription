<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SwitchLocaleController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            "locale" => ["required", Rule::in(array_keys(config('app.supported_locales')))]
        ]);

        if (auth("web")->check()) {
            $user = auth_user();

            $user->locale = $request->locale;

            $user->save();
        } else {
            session()->put('locale', $request->locale);
        }

        return back();
    }
}
