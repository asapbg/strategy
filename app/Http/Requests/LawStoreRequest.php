<?php

namespace App\Http\Requests;

use App\Models\Law;
use Illuminate\Foundation\Http\FormRequest;

class LawStoreRequest extends FormRequest
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
            'institution_id' => ['array']
        ];
        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:law'];
        }

        foreach (config('available_languages') as $lang) {
            foreach (Law::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
