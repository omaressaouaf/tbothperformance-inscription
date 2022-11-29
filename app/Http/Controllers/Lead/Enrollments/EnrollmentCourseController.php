<?php

namespace App\Http\Controllers\Lead\Enrollments;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Enrollment;

class EnrollmentCourseController extends Controller
{
    public function edit(Enrollment $enrollment)
    {
        return inertia("Lead/Enrollments/Course", [
            "enrollment" => $enrollment,
            "courses" => Course::orderBy("category_id", "desc")->queryFromRequest()->get(),
            "courseCategories" => CourseCategory::latest()->get()
        ]);
    }
}
