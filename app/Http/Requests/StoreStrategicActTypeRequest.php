<?php

namespace App\Http\Requests;

use App\Models\StrategicActType;
use Illuminate\Foundation\Http\FormRequest;

class StoreStrategicActTypeRequest extends FormRequest
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
        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:strategic_act_type'];
        }

        foreach (config('available_languages') as $lang) {
            foreach (StrategicActType::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
