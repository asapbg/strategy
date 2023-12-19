<?php

namespace App\Http\Requests;

use App\Models\OgpArea;
use App\Models\OgpPlanArrangement;
use Illuminate\Foundation\Http\FormRequest;

class OgpPlanArrangementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
        $rules = [
            'from_date' => '',
            'to_date' => '',
        ];

        foreach (config('available_languages') as $lang) {
            foreach (OgpPlanArrangement::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
