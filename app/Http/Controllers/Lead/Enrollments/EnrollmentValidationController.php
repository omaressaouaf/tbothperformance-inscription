<?php

namespace App\Http\Controllers\Lead\Enrollments;

use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lead\Enrollments\ValidateEnrollmentRequest;

class EnrollmentValidationController extends Controller
{
    public function edit(Enrollment $enrollment)
    {
        return inertia("Lead/Enrollments/Validation", compact("enrollment"));
    }

    public function update(ValidateEnrollmentRequest $request, Enrollment $enrollment)
    {
        $enrollment->update([
            ...$request->validated(),
            "completed_at" => now(),
            "status" => EnrollmentStatus::Complete
        ]);

        if (!$enrollment->cpf_dossier_number) {
            session()->flash("openCpfLink", $enrollment->cpf_link);
        }

        return redirect()->route("lead.dashboard");
    }
}
