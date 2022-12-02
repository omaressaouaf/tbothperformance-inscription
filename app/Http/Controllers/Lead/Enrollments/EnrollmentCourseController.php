<?php

namespace App\Http\Controllers\Lead\Enrollments;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\CourseCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lead\Enrollments\UpdateEnrollmentCourseRequest;

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

    public function update(UpdateEnrollmentCourseRequest $request, Enrollment $enrollment)
    {
        $enrollment->update($request->validated());

        return redirect()->route("lead.enrollments.financing.edit", [$enrollment]);
    }
}
