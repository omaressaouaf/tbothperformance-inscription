<?php

namespace App\Models;

use App\Enums\EnrollmentStatus;
use App\Enums\FinancingType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        "lead_data" => "array",
        "financing_type" => FinancingType::class,
        "status" => EnrollmentStatus::class
    ];

    protected $appends = ["next_step", "next_edit_url"];

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

    protected function nextStep(): Attribute
    {
        return Attribute::get(
            function ($value, $attributes) {
                if ($attributes["completed_at"]) {
                    return -1;
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

                if ($this->next_step === 3) {
                    return route("lead.enrollments.validation.edit", [$attributes["id"]]);
                }

                if ($this->next_step === 2) {
                    return route("lead.enrollments.financing.edit", [$attributes["id"]]);
                }

                return route("lead.enrollments.course.edit", [$attributes["id"]]);
            }
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
