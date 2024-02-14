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
            'id' => ['nullable', 'numeric'],
            'sd' => ['required', 'numeric', 'exists:strategic_document,id'],
            'doc' => ['nullable', 'numeric'],
            'strategic_document_level_id' => ['required', 'numeric'],
            'accept_act_institution_type_id' => ['required', 'numeric'],
            'strategic_document_type_id' => ['required', 'numeric'],
            'public_consultation_id' => ['nullable', 'numeric'],
            'pris_act_id' => ['nullable', 'numeric'],
            'date_expiring_indefinite' => ['nullable', 'numeric'],
            'document_date_accepted' => 'required|date',
            'document_date_expiring' => ['required_without:date_expiring_indefinite', 'date', 'nullable'],
            'link_to_monitorstat' => ['nullable', 'string', 'max:1000', 'url', 'regex:/^(https?:\/\/)/'],
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

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'document_date_expiring.required_without' => 'Полето е задължително, когато датата на изтичане не е неограничена.',
        ];
    }
}
