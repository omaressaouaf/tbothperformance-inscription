<?php

namespace App\Http\Controllers\Lead\Enrollments;

use App\Models\Enrollment;
use App\Enums\FinancingType;
use App\Enums\EnrollmentStatus;
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
        $enrollment->update([
            ...$request->validated(),
            "completed_at" => now(),
            "status" => EnrollmentStatus::Complete
        ]);

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

            return redirect()->route("lead.enrollments.payment.edit", [$enrollment]);
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
