<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\User;
use App\Services\DataTableService;
use App\Http\Controllers\Controller;

class LeadController extends Controller
{
    public function index()
    {
        return inertia("Admin/Leads/Index", [
            "leads" => DataTableService::get(Lead::withCount("enrollments"))
        ]);
    }

    public function show(Lead $lead)
    {
        return inertia("Admin/Leads/Show", [
            "lead" => $lead->loadCount(["enrollments"])->loadRequestedTab(request("tab")),
            "users" => User::all()
        ]);
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return back()->with('successMessage',  __('Item deleted successfully'));
    }
}
