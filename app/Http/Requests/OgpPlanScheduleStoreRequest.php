<?php

namespace App\Http\Requests;

use App\Models\OgpPlanSchedule;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class OgpPlanScheduleStoreRequest extends FormRequest
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
        $rules = [
            'id' => ['required', 'numeric'],
            'plan' => ['required', 'numeric', 'exists:ogp_plan,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:end_date'],
        ];
        return $this->getRules($rules, OgpPlanSchedule::translationFieldsProperties());
    }
}
