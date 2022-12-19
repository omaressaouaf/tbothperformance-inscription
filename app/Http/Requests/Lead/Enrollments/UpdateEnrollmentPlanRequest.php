<?php

namespace App\Http\Requests\Lead\Enrollments;

use App\Enums\FinancingType;
use Illuminate\Foundation\Http\FormRequest;

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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "plan_id" => "required",
            "start_date" => [
                "required",
                "date",
                function ($attribute, $value, $fail) {
                    $courseStartDateMin = today()->addWeekdays(
                        $this->financing_type === FinancingType::CPF ? 14 : 1
                    );

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
