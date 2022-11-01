<?php

namespace App\Models;

use App\Traits\QueryableFromRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plan extends Model
{
    use HasFactory, QueryableFromRequest;

    protected $guarded = [];

    protected $casts = [
        "features" => "array"
    ];

    public $searchable = ["name", "price", "features", "created_at"];

    public $defaultSort = "-created_at";

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)->withPivot(["cpf_link"]);
    }
}
