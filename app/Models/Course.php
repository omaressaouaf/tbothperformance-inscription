<?php

namespace App\Models;

use App\Traits\QueryableFromRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory, QueryableFromRequest;

    protected $guarded = [];

    public $searchable = ["title", "certificate", "created_at"];

    public $searchableRelations = [
        "category" => ["name"],
        "plans" => ["name"]
    ];

    public $filters = ["title", "certificate", "category.name", "plan.name"];

    public $exactFilters = ["created_at"];

    public $defaultSort = "-created_at";

    public $sorts = ["created_at", "updated_at"];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, "category_id");
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class)->withPivot(["cpf_link"]);
    }
}
