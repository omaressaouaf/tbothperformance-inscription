<?php

namespace App\Http\Requests\Lead\Enrollments;

use App\Enums\FinancingType;
use Illuminate\Foundation\Http\FormRequest;

class ValidateEnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->financing_type === FinancingType::Manual->value) {
            $this->merge([
                "cpf_dossier_number" => null
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "cpf_dossier_number" => "nullable",
        ];
    }
}
