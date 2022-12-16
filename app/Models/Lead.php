<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use App\Enums\EnrollmentStatus;
use App\Enums\YearsWorkedInFrance;
use Illuminate\Support\Facades\DB;
use App\Enums\ProfessionalSituation;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Grosv\LaravelPasswordlessLogin\Traits\PasswordlessLogin;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Lead extends Authenticatable
{
    use HasFactory, Notifiable, PasswordlessLogin, Billable;

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

                if ($lead->hasStripeId()) {
                    $lead->syncStripeCustomerDetails();
                }
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

    protected function fullName(): Attribute
    {
        return Attribute::get(
            fn ($value, $attributes) => $attributes["first_name"] . " " . $attributes["last_name"]
        );
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

    public function stripeName()
    {
        return $this->full_name;
    }

    public function stripePreferredLocales()
    {
        return [$this->locale];
    }
}
