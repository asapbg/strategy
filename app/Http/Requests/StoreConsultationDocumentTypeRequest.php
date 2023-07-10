<?php

namespace App\Http\Requests;

use App\Models\ConsultationDocumentType;
use Illuminate\Foundation\Http\FormRequest;

class StoreConsultationDocumentTypeRequest extends FormRequest
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
            'consultation_level_id' => ['required', 'numeric'],
            'act_type_id' => ['required', 'numeric'],
        ];

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:act_type'];
        }

        foreach (config('available_languages') as $lang) {
            foreach (ConsultationDocumentType::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
