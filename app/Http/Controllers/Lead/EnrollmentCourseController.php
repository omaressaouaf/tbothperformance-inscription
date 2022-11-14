<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;

class EnrollmentCourseController extends Controller
{
    public function edit(Enrollment $enrollment)
    {
        return inertia("Lead/Enrollments/Course", compact("enrollment"));
    }
}
