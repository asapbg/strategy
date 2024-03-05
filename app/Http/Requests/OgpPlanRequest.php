<?php

namespace App\Http\Requests;

use App\Models\ActType;
use App\Models\OgpArea;
use App\Models\OgpPlan;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class OgpPlanRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        $id = $this->request->get('id', 0);

        $rules = [
            'from_date' => ['required', 'date', 'before:to_date'],
            'to_date' => ['required', 'date'],
            'active' => ['required', 'numeric'],
            'ogp_status_id' => ['nullable', 'numeric'],
            'ogp_area' => ['nullable', 'numeric', 'exists:ogp_area,id'],
        ];

        return $this->getRules($rules, OgpPlan::translationFieldsProperties());
    }
}
