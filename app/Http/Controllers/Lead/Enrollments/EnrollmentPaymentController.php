<?php

namespace App\Http\Controllers\Lead\Enrollments;

use App\Models\Enrollment;
use App\Http\Controllers\Controller;

class EnrollmentPaymentController extends Controller
{
    public function edit(Enrollment $enrollment)
    {
        return "stripe checkout session";
    }
}
