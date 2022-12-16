<?php

namespace App\Http\Controllers\Lead\Enrollments;

use Inertia\Inertia;
use App\Models\Enrollment;
use App\Enums\FinancingType;
use App\Enums\EnrollmentStatus;
use App\Jobs\FulFillEnrollment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Lead\Enrollments\ValidateEnrollmentRequest;

class EnrollmentValidationController extends Controller
{
    public function edit(Enrollment $enrollment)
    {
        if ($enrollment->financing_type === FinancingType::Manual) {
            $enrollment->prepareSignatureRequestForContract();
        }

        return inertia("Lead/Enrollments/Validation", compact("enrollment"));
    }

    public function update(ValidateEnrollmentRequest $request, Enrollment $enrollment)
    {
        if ($enrollment->financing_type === FinancingType::CPF) {
            return $this->handleCpfFinancingValidation($request, $enrollment);
        } else {
            return $this->handleManualFinancingValidation($enrollment);
        }
    }

    private function handleCpfFinancingValidation(ValidateEnrollmentRequest $request, Enrollment $enrollment)
    {
        FulFillEnrollment::dispatchSync($enrollment);

        if (!$enrollment->cpf_dossier_number) {
            session()->flash("openCpfLink", $enrollment->cpf_link);
        }

        return redirect()->route("lead.dashboard");
    }

    private function handleManualFinancingValidation(Enrollment $enrollment)
    {
        try {
            $enrollment->syncSignatureRequestFile();

            $enrollment->update(["status" => EnrollmentStatus::ContractSigned]);

            return Inertia::location(route("lead.enrollments.payment.checkout", [$enrollment]));
        } catch (\LogicException $e) {
            throw ValidationException::withMessages([
                'contract' => __("validation.required", ["attribute" => __("validation.attributes.contract")])
            ]);
        } catch (\Exception $e) {
            Log::error("Exception Happened in EnrollmentValidationController@update : ",  [
                'exception message' => $e->getMessage()
            ]);

            session()->flash('errorMessage',  __('Unexpected error happened'));

            return back();
        }
    }
}
