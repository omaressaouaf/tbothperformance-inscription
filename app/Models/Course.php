<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Traits\QueryableFromRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory, QueryableFromRequest;

    protected $guarded = [];

    protected $appends = ["image_url"];

    public $searchable = ["title", "certificate", "created_at"];

    public $searchableRelations = [
        "category" => ["name"],
        "plans" => ["name"]
    ];

    public $filters = ["title", "certificate", "created_at", "category.name", "plans.name"];

    public $exactFilters = ["eligible_for_cpf"];

    public $defaultSort = "-created_at";

    public $sorts = ["created_at", "updated_at"];

    protected static function booted()
    {
        static::deleting(function ($course) {
            DB::afterCommit(function () use ($course) {
                if ($course->image_path) {
                    Storage::disk("public")->delete($course->image_path);
                }
            });
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, "category_id");
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class)->withPivot(["cpf_link"]);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(
            fn ($value, $attributes) => $attributes["image_path"]
                ? url('storage/' . $attributes["image_path"])
                : "https://via.placeholder.com/400x250?text=Formation"
        );
    }

    public function syncPlans(array|Collection $plans = []): void
    {
        $plans = collect($plans)
            ->keyBy("id")
            ->map(fn ($plan) => ["cpf_link" => $plan["pivot"]['cpf_link'] ?? null])
            ->toArray();

        $this->plans()->sync($plans);
    }
}
