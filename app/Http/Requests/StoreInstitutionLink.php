<?php

namespace App\Http\Requests;

use App\Models\InstitutionLink;
use Illuminate\Foundation\Http\FormRequest;

class StoreInstitutionLink extends FormRequest
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
            'id' => ['required', 'numeric', 'exists:institution'],
            'link' => ['required', 'string', 'max:1000'],
        ];

        foreach (config('available_languages') as $lang) {
            foreach (InstitutionLink::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
