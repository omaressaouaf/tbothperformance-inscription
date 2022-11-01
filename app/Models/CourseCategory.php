<?php

namespace App\Models;

use App\Traits\QueryableFromRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseCategory extends Model
{
    use HasFactory, QueryableFromRequest;

    protected $guarded = [];

    public $searchable = ["name", "created_at"];

    public $defaultSort = "-created_at";

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, "category_id");
    }
}
