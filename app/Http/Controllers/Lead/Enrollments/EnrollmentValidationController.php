<?php

namespace App\Http\Controllers\Lead\Enrollments;

use App\Models\Enrollment;
use App\Http\Controllers\Controller;

class EnrollmentValidationController extends Controller
{
    public function edit(Enrollment $enrollment)
    {
        return inertia("Lead/Enrollments/Validation", compact("enrollment"));
    }
}
