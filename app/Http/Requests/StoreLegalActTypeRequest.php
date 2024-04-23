<?php

namespace App\Http\Requests;

use App\Models\ActType;
use App\Models\LegalActType;
use Illuminate\Foundation\Http\FormRequest;

class StoreLegalActTypeRequest extends FormRequest
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
        $rules['in_pris'] = ['nullable', 'numeric'];
        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:legal_act_type'];
        }

        foreach (config('available_languages') as $lang) {
            foreach (LegalActType::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
