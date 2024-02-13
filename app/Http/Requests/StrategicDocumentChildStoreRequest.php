<?php

namespace App\Http\Requests;

use App\Models\StrategicDocumentChildren;
use Illuminate\Foundation\Http\FormRequest;

class StrategicDocumentChildStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'sd' => ['required', 'numeric', 'exists:strategic_document,id'],
            'doc' => ['nullable', 'numeric'],
            'id' => ['nullable', 'numeric'],
        ];

        $defaultLang = config('app.default_lang');
        foreach (config('available_languages') as $lang) {
            foreach (StrategicDocumentChildren::translationFieldsProperties() as $field => $properties) {
                $fieldName = $field . '_' . $lang['code'];
                $mainLang = $lang['code'] == $defaultLang;
                $fieldRules = $properties['rules'];
                if(isset($properties['required_all_lang']) && !$properties['required_all_lang'] && !$mainLang) {
                    if (($key = array_search('required', $fieldRules)) !== false) {
                        if(empty(request()->input($fieldName))){
                            $fieldRules = [];
                        } else{
                            unset($fieldRules[$key]);
                        }
                    }
                }

                if(sizeof($fieldRules)) {
                    $rules[$fieldName] = $fieldRules;
                }
            }
        }
        return $rules;
    }
}
