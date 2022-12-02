<?php

namespace App\Http\Requests\Lead\Enrollments;

use App\Enums\FinancingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEnrollmentPlanRequest extends FormRequest
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
                "cpf_start_date" => null
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
            "plan_id" => "required",
            "cpf_start_date" => [
                'nullable',
                Rule::requiredIf($this->enrollment->financing_type === FinancingType::CPF),
                "date",
                function ($attribute, $value, $fail) {
                    $courseStartDateMin = today()->addWeekdays(14);

                    if (parse_date($value)->isBefore($courseStartDateMin)) {
                        $fail(
                            __(
                                "validation.after",
                                [
                                    "attribute" => __("validation.attributes.{$attribute}"),
                                    "date" => $courseStartDateMin,
                                ]
                            )
                        );
                    }
                },
            ]
        ];
    }
}
