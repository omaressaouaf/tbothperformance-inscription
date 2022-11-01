<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateCourseRequest extends FormRequest
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
            "title" => "required",
            "certificate" => "nullable",
            "image" => "nullable|image|max:10000",
            "eligible_for_cpf" => "required|boolean",
            "category_id" => "nullable",
            "plans" => "required|array",
            "plans.*.id" => "required",
            "plans.*.pivot.cpf_link" => "nullable|url"
        ];
    }
}
