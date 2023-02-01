<?php

namespace App\Http\Controllers\Admin;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Enums\EnrollmentStatus;
use App\Enums\FinancingType;
use App\Enums\PaymentMethod;
use App\Services\DataTableService;
use App\Http\Controllers\Controller;
use App\Jobs\FulFillEnrollment;
use Illuminate\Validation\Rules\Enum;

class EnrollmentController extends Controller
{
    public function index()
    {
        return inertia("Admin/Enrollments/Index", [
            "enrollments" => DataTableService::get(Enrollment::with(["lead", "course", "plan"]))
        ]);
    }

    public function show(Enrollment $enrollment)
    {
        return inertia("Admin/Enrollments/Show", [
            "enrollment" => $enrollment->load(["completedBy", "canceledBy", "lead", "course", "plan"])
        ]);
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return back()->with('successMessage',  __('Item deleted successfully'));
    }

    public function cancel(Enrollment $enrollment)
    {
        abort_if(
            $enrollment->status === EnrollmentStatus::Complete || $enrollment->status === EnrollmentStatus::Canceled,
            403
        );

        $enrollment->markAsCanceled();

        return back();
    }

    public function toggleProcessed(Enrollment $enrollment)
    {
        abort_if(
            $enrollment->status !== EnrollmentStatus::Complete,
            403
        );

        $enrollment->update(["processed" => !$enrollment->processed]);

        return back();
    }

    public function complete(Request $request, Enrollment $enrollment)
    {
        abort_unless(
            $enrollment->status === EnrollmentStatus::ContractSigned &&
                $enrollment->financing_type === FinancingType::Manual,
            403
        );

        $request->validate([
            "payment_method" => ["required", new Enum(PaymentMethod::class), "not_in:klarna,card"],
            "paid_at" => "required|date"
        ]);

        FulFillEnrollment::dispatchSync(
            $enrollment,
            PaymentMethod::tryFrom($request->payment_method),
            $request->paid_at,
            auth_user("web")
        );

        return back();
    }
}
