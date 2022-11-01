<?php

namespace App\Http\Controllers\Admin;

use App\Services\DataTableService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUpdatePlanRequest;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        return inertia('Admin/Plans/Index', [
            'plans' => DataTableService::get(Plan::query())
        ]);
    }

    public function store(StoreUpdatePlanRequest $request)
    {
        Plan::create($request->validated());

        session()->flash('successMessage',  __('Item created successfully'));

        return response()->json([
            "created" => true
        ]);
    }

    public function update(StoreUpdatePlanRequest $request, Plan $plan)
    {
        $plan->update($request->validated());

        session()->flash('successMessage',  __('Item updated successfully'));

        return response()->json([
            "updated" => true
        ]);
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return back()->with('successMessage',  __('Item deleted successfully'));
    }
}
