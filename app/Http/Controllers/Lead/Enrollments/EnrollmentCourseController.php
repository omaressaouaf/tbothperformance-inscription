<?php

namespace App\Http\Controllers\Lead\Enrollments;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Models\CourseCategory;
use App\Http\Controllers\Controller;

class EnrollmentCourseController extends Controller
{
    public function edit(Enrollment $enrollment)
    {
        return inertia("Lead/Enrollments/Course", [
            "enrollment" => $enrollment,
            "courses" => Course::with("category")->orderBy("category_id", "desc")->queryFromRequest()->get(),
            "courseCategories" => CourseCategory::latest()->get()
        ]);
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            "course_id" => "required",
        ]);

        $enrollment->update($validated);

        return redirect()->route("lead.enrollments.financing.edit", [$enrollment]);
    }
}
