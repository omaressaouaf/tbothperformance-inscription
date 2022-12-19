<?php

namespace App\Listeners;

use App\Models\Enrollment;
use App\Enums\PaymentMethod;
use Laravel\Cashier\Cashier;
use App\Jobs\FulFillEnrollment;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener
{
    public function handle(WebhookReceived $event)
    {
        if ($event->payload['type'] === 'checkout.session.completed') {
            $this->handleCheckoutSessionCompleted($event->payload);
        }
    }

    private function handleCheckoutSessionCompleted(array $payload)
    {
        $enrollmentId = $payload["data"]["object"]["metadata"]["enrollment_id"] ?? null;

        $enrollment = $enrollmentId ? Enrollment::find($enrollmentId) : null;

        $checkoutSession = Cashier::stripe()->checkout->sessions->retrieve($payload["data"]["object"]["id"], [
            "expand" => ["payment_intent.payment_method"]
        ]);

        $paymentMethod = $checkoutSession->payment_intent->payment_method->type;

        if ($enrollment && $checkoutSession->payment_status === "paid") {
            FulFillEnrollment::dispatchSync(
                $enrollment,
                PaymentMethod::tryFrom($paymentMethod) ?? PaymentMethod::Card
            );
        }

        return response("Webhook handled", 200);
    }
}
