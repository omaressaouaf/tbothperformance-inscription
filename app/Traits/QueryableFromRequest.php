<?php

namespace App\Traits;

use Exception;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\Exceptions\InvalidIncludeQuery;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;

trait QueryableFromRequest
{
    use Searchable, Selectable;

    public function scopeQueryFromRequest($query, string $sortBy = null, bool $splitIntoTerms = false)
    {
        try {
            $query = QueryBuilder::for($query);

            if (count($this->getAllFilters())) $query = $query->allowedFilters($this->getAllFilters($splitIntoTerms));

            if (count($this->getIncludes())) $query = $query->allowedIncludes($this->getIncludes());

            if (count($this->getSorts())) $query = $query->allowedSorts($this->getSorts());

            if ($sortBy) {
                $query = $query->defaultSort($sortBy);
            } else if (count($this->getDefaultSorts())) {
                $query = $query->defaultSort(...$this->getDefaultSorts());
            } else {
                $query = $query->defaultSort('-created_at');
            }

            return $query;
        } catch (Exception $e) {
            if (!in_array($e::class, [
                InvalidFilterQuery::class,
                InvalidIncludeQuery::class,
                InvalidSortQuery::class,
            ])) {
                Log::error("Exception Happened in QueryableFromRequest@scopeQueryFromRequest : ",  [
                    'exception message' => $e->getMessage()
                ]);
            }

            return $query;
        }
    }

    /**
     * Get normal filters
     *
     * @return array
     */
    private function getFilters(): array
    {
        return $this->filters ?? [];
    }

    /**
     * Get exact filters
     *
     * @return array
     */
    private function getExactFilters(): array
    {
        $exactFilters = [];

        if (isset($this->exactFilters)) {
            foreach ($this->exactFilters as $exactFilter) {
                array_push($exactFilters, AllowedFilter::exact($exactFilter));
            }
        }

        return $exactFilters;
    }

    /**
     * Get scope filters
     *
     * @return array
     */
    private function getScopeFilters(): array
    {
        $scopeFilters = [];

        if (isset($this->scopeFilters)) {
            foreach ($this->scopeFilters as $scopeFilter) {
                array_push($scopeFilters, AllowedFilter::scope($scopeFilter));
            }
        }

        return $scopeFilters;
    }

    /**
     * Get callback filters
     *
     * @return array
     */
    private function getCallbackFilters(): array
    {
        $callbackFilters = [];

        if (isset($this->callbackFilters)) {
            foreach ($this->callbackFilters as $key => $value) {

                $callable = is_array($value) && isset($value["callable"])
                    ? $value["callable"]
                    : $value;

                $callbackFilter = AllowedFilter::callback($key, $callable);

                if (is_array($value) && isset($value["default"])) {
                    $callbackFilter->default($value["default"]);
                }

                array_push($callbackFilters, $callbackFilter);
            }
        }

        return $callbackFilters;
    }

    /**
     * Get all filters combined
     *
     * @param bool $splitIntoTerms
     * @return array
     */
    private function getAllFilters(bool $splitIntoTerms = false): array
    {
        return array_merge(
            $this->getFilters(),
            $this->getExactFilters(),
            $this->getScopeFilters(),
            $this->getCallbackFilters(),
            [AllowedFilter::callback('search_query', fn (Builder $query, $search) => $query->search($search, $splitIntoTerms))]
        );
    }

    /**
     * Get sorts
     *
     * @return array
     */
    private function getSorts(): array
    {
        return $this->sorts ?? [];
    }

    /**
     * Get default sorts
     *
     * @return array
     */
    private function getDefaultSorts(): array
    {
        if ($this->defaultSorts && is_array($this->defaultSorts)) return $this->defaultSorts;

        if ($this->defaultSort) return [$this->defaultSort];

        return [];
    }

    /**
     * Get includes
     *
     * @return array
     */
    private function getIncludes(): array
    {
        return $this->includes ?? [];
    }
}
