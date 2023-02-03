<?php

namespace App\Http\Controllers\Lead\Meetings;

use App\Http\Controllers\Controller;

class MeetingController extends Controller
{
    public function create()
    {
        return inertia("Lead/Meetings/Create");
    }
}
