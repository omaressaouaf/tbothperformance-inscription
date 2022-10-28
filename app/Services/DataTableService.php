<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Maatwebsite\Excel\Concerns\FromCollection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

class DataTableService
{
    private static int $perPage = 10;

    /**
     * Append datatable filtering and get the data
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Database\Eloquent\Builder $builder
     * @param string $sortBy
     * @param bool $paginate
     * @param bool $simplePaginate applicable when paginate is true
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public static function get(
        Builder|BuilderContract $builder,
        string $sortBy = null,
        bool $paginate = true,
        bool $simplePagination = false,
        int $page = null,
    ): Collection | PaginatorContract {
        $sortBy = $simplePagination ?
            '-created_at'
            : ($sortBy ? "-{$sortBy}" : null);

        $builder = $builder->queryFromRequest($sortBy);

        $perPage = request('perPage') ?? static::$perPage;

        if ($paginate) {
            $paginationArguments = [$perPage, ['*'], 'page', $page ?? null];

            return $simplePagination
                ? $builder->simplePaginate(...$paginationArguments)->withQueryString()
                : $builder->paginate(...$paginationArguments)->withQueryString();
        }

        return $builder->get();
    }

    /**
     * Exports the data using Excel package
     *
     * @param \Maatwebsite\Excel\Concerns\FromCollection $exportObject
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public static function export(FromCollection $exportObject, string $filename): BinaryFileResponse
    {
        $format = request()->format ?? "xlsx";

        return Excel::download($exportObject, __($filename) . '.' . $format);
    }
}
