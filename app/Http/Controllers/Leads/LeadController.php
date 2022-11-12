<?php

namespace App\Http\Controllers\Leads;

use App\Http\Controllers\Controller;

class LeadController extends Controller
{
    public function enroll()
    {
        return inertia("Lead/Enroll");
    }
}
