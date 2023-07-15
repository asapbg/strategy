<?php

namespace App\Http\Requests;

use App\Models\StrategicDocument;
use Illuminate\Foundation\Http\FormRequest;

class StoreStrategicDocumentRequest extends FormRequest
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
            'strategic_document_level_id' => ['required', 'numeric'],
            'policy_area_id' => ['required', 'numeric'],
            'strategic_document_type_id' => ['required', 'numeric'],
            'strategic_act_type_id' => ['required', 'numeric'],
            'document_number' => ['required', 'string'],
            'authority_accepting_strategic_id' => ['required', 'numeric'],
            'document_date' => ['required', 'date'],
            'consultation_number' => ['required', 'string'],
            'active' => ['boolean'],
        ];

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:strategic_document'];
        }

        foreach (StrategicDocument::translationFieldsProperties() as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }

        return $rules;
    }
}
