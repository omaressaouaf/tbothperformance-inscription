<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilderRequest;

trait Searchable
{
    public function scopeSearch(Builder $query, mixed $search, bool $splitIntoTerms = false)
    {
        $search = $this->normalizeSearch($search);

        $searchTerms = $splitIntoTerms ? $this->getSearchTerms($search) : [$search];

        foreach ($searchTerms as $term) {
            $query->where(
                fn (Builder $query) => $query->searchThroughColumns($term)->searchThroughRelations($term)
            );
        }
    }

    public function scopeSearchThroughColumns(Builder $query, string $term)
    {
        foreach ($this->getSearchableColumns() as $column) {
            $query->orWhere($column, "like", '%' . $term . '%');
        }

        return $query;
    }

    public function scopeSearchThroughRelations(Builder $query, string $term)
    {
        $query->orWhere(function ($query) use ($term) {
            foreach ($this->getSearchableRelations() as $relation => $columns) {
                $query->orWhereHas($relation, function ($query) use ($columns, $term) {
                    foreach ($columns as $key => $column) {
                        $chainingWhere = $key === 0 ? "where" : "orWhere";
                        $query->{$chainingWhere}($column, "like", '%' . $term . '%');
                    }
                });
            }
        });

        return $query;
    }

    /**
     * Normalize the given search into string if it's an array due to spatie filter value delimter
     *
     * @return string
     */
    private function normalizeSearch(array|string $search): string
    {
        return is_array($search)
            ? implode(QueryBuilderRequest::getFilterArrayValueDelimiter(), $search)
            : $search;
    }

    /**
     * Get search terms from search query
     *
     * @param string $search
     * @return array
     */
    private function getSearchTerms(string $search,): array
    {
        // We explode the search into search terms when there are spaces only if it starts and finishes with double quotes (inspired by google) . e.g : ("search exactly for this entire phrase")
        if (Str::startsWith($search, '"') && Str::endsWith($search, '"')) {
            return [Str::of($search)->replaceFirst('"', '')->replaceLast('"', '')->__toString()];
        } else {
            return explode(" ", $search);
        }
    }

    /**
     * Get searchable columns
     *
     * @return array
     */
    private function getSearchableColumns(): array
    {
        return $this->searchable ?? [];
    }

    /**
     * Get searchable relations
     *
     * @return array
     */
    private function getSearchableRelations(): array
    {
        return $this->searchableRelations ?? [];
    }
}
