<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Services\DataTableService;

class EnrollmentController extends Controller
{
    public function index()
    {
        return inertia("Admin/Enrollments/Index", [
            "enrollments" => DataTableService::get(Enrollment::with(["lead", "course", "plan"]))
        ]);
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return back()->with('successMessage',  __('Item deleted successfully'));
    }
}
