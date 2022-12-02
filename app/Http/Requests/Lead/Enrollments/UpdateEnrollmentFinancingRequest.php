<?php

namespace App\Http\Requests\Lead\Enrollments;

use App\Enums\FinancingType;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentFinancingRequest extends FormRequest
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
                "cpf_amount" => null
            ]);
        }

        if (!$this->enrollment->course->eligible_for_cpf) {
            $this->merge([
                "financing_type" => FinancingType::Manual->value
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
            "financing_type" => ["required", new Enum(FinancingType::class)],
            "cpf_amount" => [
                "nullable",
                Rule::requiredIf($this->financing_type === FinancingType::CPF->value),
                "numeric",
                "min:0.01"
            ]
        ];
    }
}
