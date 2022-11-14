<?php

namespace App\Http\Controllers\Lead;

use App\Models\Lead;
use Illuminate\Http\Request;
use App\Mail\PasswordlessLinkMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthenticatedSessionController extends Controller
{
    public function passwordlessLink()
    {
        return inertia("Lead/PasswordlessLink");
    }

    public function sendPasswordlessLink(Request $request)
    {
        $request->validate([
            "email" => "required|email|exists:leads"
        ], [
            "email.exists" => __("auth.failed")
        ]);

        $lead = Lead::where("email", $request->email)->firstOrFail();

        $passwordlessLink = $lead->createPasswordlessLoginLink();

        Mail::to($lead->email)->send(new PasswordlessLinkMail($passwordlessLink));

        return back()->with("successMessage", __("A login email was sent. check your inbox"));
    }

    public function destroy(Request $request)
    {
        $locale = session()->get('locale');

        Auth::guard('lead')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->put('locale', $locale);

        return redirect(route("lead.enroll"));
    }
}
