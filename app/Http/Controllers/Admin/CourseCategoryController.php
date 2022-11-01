<?php

namespace App\Http\Controllers\Admin;

use App\Services\DataTableService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUpdateCourseCategoryRequest;
use App\Models\CourseCategory;

class CourseCategoryController extends Controller
{
    public function index()
    {
        return inertia('Admin/CourseCategories/Index', [
            'courseCategories' => DataTableService::get(CourseCategory::query())
        ]);
    }

    public function store(StoreUpdateCourseCategoryRequest $request)
    {
        CourseCategory::create($request->validated());

        session()->flash('successMessage',  __('Item created successfully'));

        return response()->json([
            "created" => true
        ]);
    }

    public function update(StoreUpdateCourseCategoryRequest $request, CourseCategory $courseCategory)
    {
        $courseCategory->update($request->validated());

        session()->flash('successMessage',  __('Item updated successfully'));

        return response()->json([
            "updated" => true
        ]);
    }

    public function destroy(CourseCategory $courseCategory)
    {
        $courseCategory->delete();

        return back()->with('successMessage',  __('Item deleted successfully'));
    }
}
