<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Services\DataTableService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        return inertia("Admin/Leads/Index", [
            "leads" => DataTableService::get(Lead::withCount("enrollments"))
        ]);
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return back()->with('successMessage',  __('Item deleted successfully'));
    }
}
