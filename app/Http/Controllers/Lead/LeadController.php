<?php

namespace App\Http\Controllers\Lead;

use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lead\StoreUpdateLeadRequest;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function enroll()
    {
        return inertia("Lead/Enroll");
    }

    public function store(StoreUpdateLeadRequest $request)
    {
        try {
            DB::beginTransaction();

            $lead = Lead::create($request->validated());

            $enrollment = $lead->enrollments()->create([
                "course_id" => request("courseId") ? Course::findOrFail(request("courseId"))->id : null
            ]);

            Auth::guard("lead")->login($lead, true);

            DB::commit();

            return redirect()
                ->route("lead.enrollments.course.edit", ["enrollment" => $enrollment->id])
                ->with("enrollmentSuccessMessage", __("Success"));
        } catch (\Exception $e) {
            DB::rollback();

            Log::error("Exception Happened in LeadController@store : ",  [
                'exception message' => $e->getMessage()
            ]);

            return back()->with('errorMessage',  __('Unexpected error happened'));
        }
    }

    public function update(StoreUpdateLeadRequest $request, Lead $lead)
    {
        $lead->update($request->validated());

        return back()->with("successMessage", __("Item updated successfully"));
    }
}
