<?php

namespace App\Models;

use App\Enums\FinancingType;
use App\Enums\PaymentMethod;
use App\Enums\EnrollmentStatus;
use App\Traits\RequiresContractSignature;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory, RequiresContractSignature;

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
}
