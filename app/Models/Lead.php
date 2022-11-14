<?php

namespace App\Models;

use App\Enums\ProfessionalSituation;
use App\Enums\YearsWorkedInFrance;
use Grosv\LaravelPasswordlessLogin\Traits\PasswordlessLogin;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function syncEnrollmentLeadData(): void
    {
        Enrollment::where("lead_id", $this->id)
            ->update([
                "lead_data" => [
                    "first_name" => $this->lead->first_name,
                    "last_name" => $this->lead->last_name,
                    "email" => $this->lead->email,
                    "phone" => $this->lead->phone,
                    "years_worked_in_france" => $this->lead->years_worked_in_france,
                    "professional_situation" => $this->lead->professional_situation,
                ]
            ]);
    }
}
