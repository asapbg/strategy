<?php

namespace App\Http\Requests;

use App\Models\RegulatoryAct;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegulatoryActRequest extends FormRequest
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
            'regulatory_act_type_id' => ['required'],
            'number' => ['required'],
        ];

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:regulatory_act'];
        }

        foreach (RegulatoryAct::translationFieldsProperties() as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }

        return $rules;
    }
}
