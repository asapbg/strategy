<?php

namespace App\Http\Requests;

use App\Models\AdvisoryChairmanType;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdvisoryChairmanTypeRequest extends FormRequest
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
            $rules['id'] = ['required', 'numeric', 'exists:advisory_chairman_type'];
        }

        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryChairmanType::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
