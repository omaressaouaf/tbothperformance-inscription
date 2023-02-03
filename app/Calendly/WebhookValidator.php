<?php

namespace App\Calendly;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WebhookValidator
{
    public function __construct(public Request $request)
    {
    }

    public function execute(): bool
    {
        [$requestSignature, $requestTimestamp] = $this->extractSignatureAndTimestamp(
            $this->request->header('Calendly-Webhook-Signature')
        );

        if (!$requestSignature || !$requestTimestamp) {
            return false;
        }

        // Verify request signature
        $expectedSignature = $this->buildExpectedSignature($requestTimestamp, $this->request->getContent());

        if ($expectedSignature !== $requestSignature) {
            return false;
        }

        // Verify request timestamp
        $requestDate = Carbon::createFromTimestamp($requestTimestamp);

        if ($requestDate->isBefore(now()->subMinutes(3))) {
            return false;
        }
        
        return true;
    }

    private function extractSignatureAndTimestamp(?string $calendlySignature = null): array
    {
        if (!$calendlySignature) return [null, null];

        // e.g : Calendly-Webhook-Signature: t=1492774577,v1=5257a869e7ea32affa62cdca3fa57a0e56ff536d0ce8e108d8bd
        $parts = explode(',', $calendlySignature);

        $requestSignature = '';
        $requestTimestamp = '';

        foreach ($parts as $part) {
            [$key, $value] = explode('=', $part);

            if ($key === 'v1') {
                $requestSignature = $value;
            }

            if ($key === 't') {
                $requestTimestamp = $value;
            }
        }

        return [$requestSignature, $requestTimestamp];
    }

    private function buildExpectedSignature(int $requestTimestamp, string $requestBody): string
    {
        $payload = $requestTimestamp . '.' . $requestBody;

        return hash_hmac('sha256', $payload, config('services.calendly.webhook_signing_key'));
    }
}
