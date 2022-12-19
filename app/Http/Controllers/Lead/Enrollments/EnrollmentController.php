<?php

namespace App\Http\Controllers\Lead\Enrollments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnrollmentController extends Controller
{
    public function store(Request $request)
    {
        $lead = auth_user("lead");

        abort_unless($lead->pendingEnrollment === null, 403);

        $enrollment = $lead->enrollments()->create();

        return redirect()->route("lead.enrollments.course.edit", ["enrollment" => $enrollment->id]);
    }
}
