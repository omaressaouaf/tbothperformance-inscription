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
use App\Models\User;
use App\Notifications\UserAssignedToEnrollmentNotification;
use Illuminate\Validation\Rules\Enum;

class EnrollmentController extends Controller
{
    public function index()
    {
        return inertia("Admin/Enrollments/Index", [
            "enrollments" => DataTableService::get(Enrollment::with(["lead", "responsibleUser", "course", "plan"])),
            "users" => User::all()
        ]);
    }

    public function show(Enrollment $enrollment)
    {
        return inertia("Admin/Enrollments/Show", [
            "enrollment" => $enrollment->load([
                "completedBy",
                "canceledBy",
                "responsibleUser",
                "lead",
                "course",
                "plan"
            ]),
            "users" => User::all()
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

    public function updateResponsibleUser(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            "responsible_user_id" => "nullable",
        ]);

        $enrollment->update(["responsible_user_id" => $request->responsible_user_id]);

        if ($request->responsible_user_id && auth_user("web")?->id !== $request->responsible_user_id) {
            $enrollment->refresh();

            $enrollment
                ->responsibleUser
                ->notify(new UserAssignedToEnrollmentNotification(auth_user("web"), $enrollment));
        }

        return back();
    }
}
