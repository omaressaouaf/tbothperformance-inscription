<?php

namespace App\Models;

use App\Enums\FinancingType;
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
        "financing_type" => FinancingType::class
    ];

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
