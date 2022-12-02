<?php

namespace App\Http\Controllers\Lead\Enrollments;

use App\Models\Enrollment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lead\Enrollments\UpdateEnrollmentFinancingRequest;

class EnrollmentFinancingController extends Controller
{
    public function edit(Enrollment $enrollment)
    {
        return inertia("Lead/Enrollments/Financing", compact("enrollment"));
    }

    public function update(UpdateEnrollmentFinancingRequest $request, Enrollment $enrollment)
    {
        $enrollment->update($request->validated());

        return redirect()->route("lead.enrollments.plan.edit", [$enrollment]);
    }
}
