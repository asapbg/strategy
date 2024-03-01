<?php

namespace App\Http\Requests\Admin;

use App\Models\OgpPlan;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class OgpDevelopPlanRequest extends FormRequest
{
    use TranslatableFieldsRules;
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
        $id = $this->request->get('id', 0);

        $rules = [
            'from_date' => ['required', 'date', 'before:to_date'],
            'to_date' => ['required', 'date'],
            'from_date_develop' => ['required', 'date', 'before:to_date_develop', 'before:from_date'],
            'to_date_develop' => ['required', 'date', 'before:from_date'],
            'active' => ['required', 'numeric'],
            'ogp_status_id' => ['nullable', 'numeric'],
            'ogp_area' => ['nullable', 'numeric', 'exists:ogp_area,id'],
        ];

        return $this->getRules($rules, OgpPlan::translationFieldsProperties());
    }
}
