<?php

namespace App\Http\Requests;

use App\Models\LegislativeInitiative;
use Illuminate\Foundation\Http\FormRequest;

class StoreLegislativeInitiativeRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            'regulatory_act_id' => ['required', 'numeric'],
            'active' => ['boolean'],
        ];

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:link'];
        }

        foreach (LegislativeInitiative::translationFieldsProperties() as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }

        return $rules;
    }
}
