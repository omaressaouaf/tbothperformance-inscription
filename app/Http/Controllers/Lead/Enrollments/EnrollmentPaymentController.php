<?php

namespace App\Http\Controllers\Lead\Enrollments;

use App\Models\Enrollment;
use App\Http\Controllers\Controller;
use Stripe\Exception\InvalidRequestException;

class EnrollmentPaymentController extends Controller
{
    public function checkout(Enrollment $enrollment)
    {
        $lead = auth_user("lead");

        return $lead->checkoutCharge($enrollment->total * 100, $enrollment->label, 1, [
            "locale" => $lead->locale,
            "success_url" => route("lead.enrollments.payment.success", [$enrollment]) . '?sessionId={CHECKOUT_SESSION_ID}',
            "cancel_url" => route("lead.dashboard"),
            "payment_method_options" => [
                "card" => [
                    "setup_future_usage" => "on_session"
                ],
                "klarna" => [
                    "setup_future_usage" => "none"
                ],
            ],
            "metadata" => [
                "enrollment_id" => $enrollment->id
            ],
            "custom_text" => [
                "submit" => [
                    "message" => __("Choose klarna if you want to split your payments into 3 installments monthly without interest")
                ]
            ]
        ]);
    }

    public function success(Enrollment $enrollment)
    {
        try {
            $checkoutSession = auth_user("lead")->stripe()->checkout->sessions->retrieve(request()->sessionId);

            if ($checkoutSession->payment_status !== "paid") {
                abort(404);
            }

            return inertia("Lead/Enrollments/PaymentSuccess", compact("enrollment"));
        } catch (InvalidRequestException $e) {
            abort(404);
        }
    }
}
