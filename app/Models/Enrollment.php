<?php

namespace App\Models;

use App\Yousign\Yousign;
use App\Enums\FinancingType;
use App\Enums\EnrollmentStatus;
use App\Enums\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        "lead_data" => "array",
        "financing_type" => FinancingType::class,
        "status" => EnrollmentStatus::class,
        "cpf_amount" => "decimal:2",
        "cpf_start_date" => "date:Y-m-d",
        "signature_request_data" => "array",
        "completed_at" => "datetime:Y-m-d H:i:s",
        "paid_at" => "datetime:Y-m-d H:i:s",
        "payment_method" => PaymentMethod::class
    ];

    protected $appends = ["next_step", "next_edit_url", "cpf_link", "total", "label"];

    protected $with = ["course"];

    public static function booted()
    {
        static::created(function ($enrollment) {
            DB::afterCommit(function () use ($enrollment) {
                $enrollment->syncLeadData();
            });
        });
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function paymentApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, "payment_approver_id");
    }

    protected function nextStep(): Attribute
    {
        return Attribute::get(
            function ($value, $attributes) {
                if ($attributes["status"] === EnrollmentStatus::Complete->value) {
                    return -1;
                }

                if ($attributes["status"] === EnrollmentStatus::ContractSigned->value) {
                    return 5;
                }

                if ($attributes["plan_id"]) {
                    return 4;
                }

                if ($attributes["financing_type"]) {
                    return 3;
                }

                if ($attributes["course_id"]) {
                    return 2;
                }

                return 1;
            }
        );
    }

    protected function nextEditUrl(): Attribute
    {
        return Attribute::get(
            function ($value, $attributes) {
                if ($this->next_step === -1) {
                    return null;
                }

                if ($this->next_step === 5) {
                    return route("lead.enrollments.payment.checkout", [$attributes["id"]]);
                }

                if ($this->next_step === 4) {
                    return route("lead.enrollments.validation.edit", [$attributes["id"]]);
                }

                if ($this->next_step === 3) {
                    return route("lead.enrollments.plan.edit", [$attributes["id"]]);
                }

                if ($this->next_step === 2) {
                    return route("lead.enrollments.financing.edit", [$attributes["id"]]);
                }

                return route("lead.enrollments.course.edit", [$attributes["id"]]);
            }
        );
    }

    protected function cpfLink(): Attribute
    {
        return Attribute::get(
            fn ()  => $this->plan ? $this->course->plans($this->plan->id)->first()->pivot->cpf_link : null
        );
    }

    protected function total(): Attribute
    {
        return Attribute::get(
            fn () => $this->plan ? $this->plan->price : null
        );
    }

    protected function label(): Attribute
    {
        return Attribute::get(
            fn ()  => $this->course && $this->plan
                ? sprintf("%s - %s", $this->course->title, $this->plan->name)
                : null
        );
    }

    public function syncLeadData(): void
    {
        if ($this->lead) {
            $this->lead_data = [
                "first_name" => $this->lead->first_name,
                "last_name" => $this->lead->last_name,
                "email" => $this->lead->email,
                "phone" => $this->lead->phone,
                "years_worked_in_france" => $this->lead->years_worked_in_france,
                "professional_situation" => $this->lead->professional_situation,
            ];

            $this->save();
        }
    }

    public function prepareSignatureRequestForContract()
    {
        if (!$this->lead_data || !is_array($this->lead_data)) {
            throw new \LogicException("Lead data is required for the contract");
        }

        $yousign = app(Yousign::class);

        if (isset($this->signature_request_data["id"]) && !is_null($this->signature_request_data["id"])) {
            $signatureRequest = $yousign->getSignatureRequest($this->signature_request_data["id"]);

            if ($signatureRequest["status"] === "expired") {
                $this->activateSignatureRequest($signatureRequest, true);
            }

            return $this->signature_request_data;
        } else {
            $signatureRequest = $this->createAndInitiateSignatureRequest();

            $this->activateSignatureRequest($signatureRequest);

            return $this->signature_request_data;
        }
    }

    private function createAndInitiateSignatureRequest(): array
    {
        $yousign = app(Yousign::class);

        $document = $yousign->uploadDocument("signable_document", public_path("contrat.pdf"));

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
                        "phone_number" => $this->lead["phone"],
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

    public function syncSignatureRequestFile(): void
    {
        $yousign = app(Yousign::class);

        $signatureRequest = $yousign->getSignatureRequest($this->signature_request_data["id"]);

        if ($signatureRequest["status"] !== "done") {
            throw new \LogicException("Signature request is not done yet");
        }

        $response = $yousign->downloadSignatureRequestFile(
            $this->signature_request_data["id"],
            ["version" => "completed", "archive" => "true"]
        );

        $path = "enrollments/{$this->id}/contrat.zip";

        Storage::put($path, $response->getBody());

        $this->update([
            "signature_request_data->file_path" => $path
        ]);
    }
}
