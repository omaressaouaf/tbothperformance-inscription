<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return inertia("Lead/Dashboard", [
            "pendingEnrollment" => request()->user("lead")->pendingEnrollment,
            "enrollments" => request()->user("lead")->enrollments
        ]);
    }
}
