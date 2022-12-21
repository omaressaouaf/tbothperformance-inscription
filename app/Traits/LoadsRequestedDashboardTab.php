<?php

namespace App\Traits;

use App\Services\DataTableService;
use Illuminate\Database\Eloquent\Relations\Relation;

trait LoadsRequestedDashboardTab
{
    public static function hasRelation(string $relation): bool
    {
        if (method_exists(get_class(), $relation)) {
            $model = new static();
            return is_a($model->$relation(), Relation::class);
        }

        return false;
    }

    public function setTabRelation(string $tab, callable $queryCallable = null): static
    {
        $builder = $this->{$tab}();

        if ($queryCallable) {
            $builder = $queryCallable($builder);
        }

        return $this->setRelation(
            $tab,
            DataTableService::get($builder)
        );
    }

    abstract public function loadRequestedTab(string|null $tab): static;
}
