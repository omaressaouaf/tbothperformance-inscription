<?php

namespace App\Http\Middleware;

use App\Enums\EnrollmentStatus;
use Closure;
use Illuminate\Http\Request;

class ValidateEnrollment
{
    public function handle(Request $request, Closure $next)
    {
        $enrollment = $request->enrollment;

        if (in_array($enrollment->status, [EnrollmentStatus::Complete, EnrollmentStatus::Canceled])) {
            return redirect(route("lead.dashboard"));
        }

        if ($this->currentStep($request) > $enrollment->next_step) {
            return redirect($enrollment->next_edit_url);
        }

        return $next($request);
    }

    private function currentStep(Request $request)
    {
        if ($request->routeIs("lead.enrollments.payment.*")) {
            return 5;
        }

        if ($request->routeIs("lead.enrollments.validation.*")) {
            return 4;
        }

        if ($request->routeIs("lead.enrollments.plan.*")) {
            return 3;
        }

        if ($request->routeIs("lead.enrollments.financing.*")) {
            return 2;
        }

        return 1;
    }
}
