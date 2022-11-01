<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\DataTableService;
use App\Http\Controllers\Controller;
use App\Traits\QueryableFromRequest;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class BulkController extends Controller
{
    public function destroy(Request $request)
    {
        $request->validate([
            "table" => "required|string",
            "ids" => "required|array",
            "ids.*" => "required"
        ]);

        $class = Str::of($request->table)->singular()->studly();
        $model = "App\\Models\\$class";

        if (!class_exists($model) || $this->modelIsNotQueryableFromRequest($model)) {
            abort(404);
        }

        $model::whereIn("id", $request->ids)->get()->each->delete();

        return back()->with('successMessage',  __('Item(s) deleted successfully'));
    }

    public function export(Request $request)
    {
        $request->validate([
            "table" => "required|string",
            "format" => ['nullable', Rule::in(['xlsx', 'csv', 'tsv', 'ods', 'xls', 'html'])]
        ]);

        $class = Str::of($request->table)->studly();
        $export = "App\\Exports\\$class" . "Export";
        $model = "App\\Models\\" . $class->singular();

        if (
            !class_exists($export) ||
            $this->modelIsNotQueryableFromRequest($model)
        ) {
            abort(404);
        }

        return DataTableService::export(new $export, "Data list");
    }

    private function modelIsNotQueryableFromRequest(string $model): bool
    {
        return !in_array(QueryableFromRequest::class, class_uses_recursive($model));
    }
}
