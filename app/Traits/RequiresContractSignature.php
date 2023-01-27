<?php

namespace App\Traits;

use App\Enums\FinancingType;
use App\Yousign\Yousign;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\RequestException;

trait RequiresContractSignature
{
    public static function bootRequiresContractSignature()
    {
        static::updated(function ($model) {
            DB::afterCommit(function () use ($model) {
                if (
                    $model->financing_type === FinancingType::Manual &&
                    $model->wasChanged(["lead_data", "course_id", "plan_id"])
                ) {
                    $model->abortSignatureRequest();
                }
            });
        });
    }

    public function prepareSignatureRequestForContract()
    {
        if (!$this->lead_data || !is_array($this->lead_data)) {
            throw new \LogicException("Lead data is required for the contract");
        }

        $yousign = app(Yousign::class);

        if (isset($this->signature_request_data["id"])) {
            try {
                $signatureRequest = $yousign->getSignatureRequest($this->signature_request_data["id"]);

                if ($signatureRequest["status"] === "expired") {
                    $this->activateSignatureRequest($signatureRequest, true);
                }
            } catch (RequestException $e) {
                $this->abortSignatureRequest(true);
            }
        }

        if (!$this->signature_request_data) {
            $signatureRequest = $this->createAndInitiateSignatureRequest();

            $this->activateSignatureRequest($signatureRequest);
        }

        return $this->fresh()->signature_request_data;
    }

    private function createAndInitiateSignatureRequest(): array
    {
        $yousign = app(Yousign::class);

        $document = $yousign->uploadDocument($this->generateContract(), "contract.pdf", [
            "nature" => "signable_document",
            "parse_anchors" => "true"
        ]);

        $signatureRequestParams = [
            "name" => $this->label,
            "delivery_mode" =>  "none",
            "expiration_date" => today()->addMonths(6)->toDateString(),
            "timezone" => "Europe/Paris",
            "documents" => [$document["id"]],
            "external_id" => "{$this->id}",
            "signers" => [
                [
                    "info" => [
                        "first_name" => $this->lead["first_name"],
                        "last_name" => $this->lead["last_name"],
                        "email" => $this->lead["email"],
                        "locale" => $this->lead["locale"]
                    ],
                    "signature_level" => "electronic_signature",
                    "signature_authentication_mode" => "no_otp"
                ]
            ]
        ];

        return $yousign->initiateSignatureRequest($signatureRequestParams);
    }

    private function activateSignatureRequest(array $signatureRequest, bool $reactivate = false): void
    {
        $yousign = app(Yousign::class);

        $signatureRequestActivation =  $reactivate
            ? $yousign->reactivateSignatureRequest(
                $signatureRequest["id"],
                ["expiration_date" => today()->addMonths(6)->toDateString()]
            )
            : $yousign->activateSignatureRequest($signatureRequest["id"]);

        $this->update(["signature_request_data" => [
            "id" => $signatureRequestActivation["id"],
            "signature_link" => $signatureRequestActivation["signers"][0]["signature_link"]
        ]]);
    }

    private function generateContract(): mixed
    {
        $path = "enrollments/{$this->id}/contract-unsigned.pdf";

        $storagePath = storage_path("app/{$path}");

        Storage::makeDirectory("enrollments/{$this->id}/");

        Pdf::loadView('pdfs.contract', ["enrollment" => $this])->setPaper('a3')->save($storagePath);

        $this->update([
            "contract_files->unsigned" => $path,
        ]);

        return file_get_contents($storagePath);
    }

    public function abortSignatureRequest(bool $onlyLocally = false): void
    {
        if (isset($this->signature_request_data["id"]) && !is_null($this->signature_request_data["id"])) {
            if (!$onlyLocally) {
                try {
                    $yousign = app(Yousign::class);

                    $signatureRequest = $yousign->getSignatureRequest($this->signature_request_data["id"]);

                    if ($signatureRequest["status"] !== "done") {
                        $yousign->cancelSignatureRequest($signatureRequest["id"], [
                            "reason" => "contractualization_aborted"
                        ]);
                    }

                    $yousign->deleteSignatureRequest($signatureRequest["id"]);
                } catch (RequestException $e) {
                }
            }

            $this->updateQuietly([
                "signature_request_data" => null
            ]);
        }
    }

    public function syncSignatureRequestFile(): void
    {
        $yousign = app(Yousign::class);

        $signatureRequest = $yousign->getSignatureRequest($this->signature_request_data["id"]);

        if ($signatureRequest["status"] !== "done") {
            throw new \LogicException("Signature request is not done yet");
        }

        $response = $yousign->downloadSignatureRequestFile(
            $this->signature_request_data["id"],
            ["version" => "completed"]
        );

        $path = "enrollments/{$this->id}/contract-signed.pdf";

        Storage::put($path, $response->getBody());

        $this->update([
            "contract_files->signed" => $path,
        ]);
    }
}
