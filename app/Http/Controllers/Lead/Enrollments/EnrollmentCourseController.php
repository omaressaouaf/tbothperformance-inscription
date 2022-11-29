<?php

namespace App\Http\Controllers\Lead\Enrollments;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;

class EnrollmentCourseController extends Controller
{
    public function edit(Enrollment $enrollment)
    {
        return inertia("Lead/Enrollments/Course", compact("enrollment"));
    }
}
