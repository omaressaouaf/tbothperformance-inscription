<?php

namespace App\Http\Controllers\Lead\Enrollments;

use App\Models\Enrollment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lead\Enrollments\UpdateEnrollmentPlanRequest;

class EnrollmentPlanController extends Controller
{
    public function edit(Enrollment $enrollment)
    {
        return inertia("Lead/Enrollments/Plan", [
            "enrollment" => $enrollment->load(["course.plans"]),
            "courseStartDateMin" => today()->addWeekdays(14)
        ]);
    }

    public function update(UpdateEnrollmentPlanRequest $request, Enrollment $enrollment)
    {
        $enrollment->update($request->validated());

        return redirect()->route("lead.enrollments.validation.edit", [$enrollment]);
    }
}
