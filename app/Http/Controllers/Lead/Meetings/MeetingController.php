<?php

namespace App\Http\Controllers\Lead\Meetings;

use Exception;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class MeetingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "event_url" => "required|url"
        ]);

        try {
            Meeting::createFromCalendlyEvent($request->event_url);

            session()->flash('successMessage',  __('Meeting has been booked successfully'));
        } catch (Exception $e) {
            Log::error("Exception Happened in MeetingController@store : ",  [
                'exception message' => $e->getMessage()
            ]);

            session()->flash(
                'errorMessage',
                __('Meeting has been booked in calendly. but an error happened in our server.')
            );
        }

        return back();
    }
}
