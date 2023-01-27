<?php

namespace App\Models;

use App\Enums\FinancingType;
use App\Enums\PaymentMethod;
use Illuminate\Support\Carbon;
use App\Enums\EnrollmentStatus;
use Illuminate\Support\Facades\DB;
use App\Services\IdGeneratorService;
use App\Traits\QueryableFromRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\RequiresContractSignature;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory, RequiresContractSignature, QueryableFromRequest;

    protected $guarded = [];

    protected $casts = [
        "lead_data" => "array",
        "financing_type" => FinancingType::class,
        "status" => EnrollmentStatus::class,
        "course_start_date" => "date:Y-m-d",
        "cpf_amount" => "decimal:2",
        "signature_request_data" => "array",
        "contract_files" => "array",
        "completed_at" => "datetime:Y-m-d H:i:s",
        "paid_at" => "datetime:Y-m-d H:i:s",
        "payment_method" => PaymentMethod::class
    ];

    protected $appends = ["next_step", "next_edit_url", "cpf_link", "total", "cpf_remaining_charges", "label"];

    protected $with = ["course"];

    public $searchable = ["number", "lead_data", "financing_type"];

    public $searchableRelations = [
        "course" => ["title"],
        "plan" => ["name"],
        "lead" => ["first_name", "last_name", "email"]
    ];

    public $filters = [
        "number",
        "lead_data",
        "course.title",
        "plan.name",
        "created_at",
        "completed_at",
    ];

    public $exactFilters = ["financing_type", "status"];

    public $defaultSort = "-created_at";

    public $sorts = ["created_at", "updated_at", "completed_at", "course_start_date"];

    public static function booted()
    {
        static::creating(function ($enrollment) {
            $enrollment->number = IdGeneratorService::generate(static::class, "number", 5, "INS-");
        });

        static::created(function ($enrollment) {
            DB::afterCommit(function () use ($enrollment) {
                $enrollment->syncLeadData();
            });
        });

        static::deleted(function ($enrollment) {
            DB::afterCommit(function () use ($enrollment) {
                Storage::deleteDirectory("enrollments/{$enrollment->id}");
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

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "completed_by_id");
    }

    public function canceledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "canceled_by_id");
    }

    protected function nextStep(): Attribute
    {
        return Attribute::get(
            function ($value, $attributes) {
                if (in_array(
                    $attributes["status"],
                    [EnrollmentStatus::Complete->value, EnrollmentStatus::Canceled->value]
                )) {
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
            fn ($value, $attributes)  => $this->plan && $attributes["financing_type"] === FinancingType::CPF->value
                ? $this->course->plans($this->plan->id)->first()->pivot->cpf_link
                : null
        );
    }

    protected function total(): Attribute
    {
        return Attribute::get(
            fn () => $this->plan ? $this->plan->price : null
        );
    }

    protected function cpfRemainingCharges(): Attribute
    {
        return Attribute::get(
            function ($value, $attributes) {
                if ($this->total === null || $attributes["cpf_amount"] === null) return null;

                if ($this->total <= $attributes["cpf_amount"]) return 0;

                return as_decimal($this->total - $attributes["cpf_amount"]);
            }
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

    protected function courseEndDate(): Attribute
    {
        return Attribute::get(
            fn ($value, $attributes) => isset($attributes["course_start_date"])
                ? parse_date($attributes["course_start_date"])->addMonths(3)
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

    public function markAsComplete(
        ?PaymentMethod $paymentMethod = null,
        Carbon|string|null $paidAt = null,
        ?User $completedBy = null
    ): void {
        if ($this->financing_type === FinancingType::Manual) {
            $this->payment_method = $paymentMethod;
            $this->completed_by_id = $completedBy?->id;
            $this->paid_at = $paidAt ?? now();
        }

        $this->status = EnrollmentStatus::Complete;
        $this->completed_at = now();

        $this->save();
    }

    public function markAsCanceled()
    {
        $this->status = EnrollmentStatus::Canceled;
        $this->canceled_by_id = auth_user("web")?->id;

        $this->save();
    }
}
