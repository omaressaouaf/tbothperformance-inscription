<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\Course;
use Illuminate\Support\Arr;
use App\Models\CourseCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUpdateCourseRequest;
use App\Services\DataTableService;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        return inertia("Admin/Courses/Index", [
            "courses" => DataTableService::get(Course::with(["category", "plans"]))
        ]);
    }

    public function create()
    {
        return inertia("Admin/Courses/CreateEdit", [
            "courseCategories" => CourseCategory::latest()->get(),
            "plans" => Plan::latest()->get()
        ]);
    }

    public function store(StoreUpdateCourseRequest $request)
    {
        try {
            DB::beginTransaction();

            $course = new Course(Arr::except($request->validated(), ["plans", "image"]));

            if ($request->file("image")) {
                $course->image_path = $request->file('image')->store("courses", "public");
            }

            $course->save();

            $course->syncPlans($request->plans);

            DB::commit();

            return redirect()->route("admin.courses.index")->with('successMessage',  __('Item created successfully'));
        } catch (\Exception $e) {
            DB::rollback();

            Log::error("Exception Happened in CourseController@store : ",  [
                'exception message' => $e->getMessage()
            ]);

            return back()->with('errorMessage',  __('Unexpected error happened'));
        }
    }

    public function edit(Course $course)
    {
        return inertia("Admin/Courses/CreateEdit", [
            "course" => $course->load("plans"),
            "courseCategories" => CourseCategory::latest()->get(),
            "plans" => Plan::latest()->get()
        ]);
    }

    public function update(StoreUpdateCourseRequest $request, Course $course)
    {
        try {
            DB::beginTransaction();

            $course->update(Arr::except($request->validated(), ["plans", "image"]));

            if ($request->file("image")) {
                if ($course->image_path) {
                    Storage::disk("public")->delete($course->image_path);
                }

                $course->update(["image_path" => $request->file('image')->store("courses", "public")]);
            }

            $course->syncPlans($request->plans);

            DB::commit();

            return back()->with('successMessage',  __('Item updated successfully'));
        } catch (\Exception $e) {
            DB::rollback();

            Log::error("Exception Happened in CourseController@update : ",  [
                'exception message' => $e->getMessage()
            ]);

            return back()->with('errorMessage',  __('Unexpected error happened'));
        }
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return back()->with('successMessage',  __('Item deleted successfully'));
    }
}
