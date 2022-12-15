<?php

namespace App\Models;

use App\Enums\EnrollmentStatus;
use App\Enums\YearsWorkedInFrance;
use Illuminate\Support\Facades\DB;
use App\Enums\ProfessionalSituation;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Grosv\LaravelPasswordlessLogin\Traits\PasswordlessLogin;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lead extends Authenticatable
{
    use HasFactory, Notifiable, PasswordlessLogin;

    protected $guard = "lead";

    protected $guarded = [];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        "years_worked_in_france" => YearsWorkedInFrance::class,
        "professional_situation" => ProfessionalSituation::class
    ];

    public static function booted()
    {
        static::updated(function ($lead) {
            DB::afterCommit(function () use ($lead) {
                $lead->syncEnrollmentLeadData();
            });
        });
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function pendingEnrollment(): HasOne
    {
        return $this->hasOne(Enrollment::class)->where("status", "!=", EnrollmentStatus::Complete)->oldestOfMany();
    }

    public function syncEnrollmentLeadData(): void
    {
        Enrollment::where("lead_id", $this->id)
            ->update([
                "lead_data" => [
                    "first_name" => $this->first_name,
                    "last_name" => $this->last_name,
                    "email" => $this->email,
                    "phone" => $this->phone,
                    "years_worked_in_france" => $this->years_worked_in_france,
                    "professional_situation" => $this->professional_situation,
                ]
            ]);
    }
}
