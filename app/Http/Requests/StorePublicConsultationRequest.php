<?php

namespace App\Http\Requests;

use App\Models\Consultations\PublicConsultation;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicConsultationRequest extends FormRequest
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
            'consultation_type_id' => ['required', 'numeric', 'exists:consultation_type,id'],
            'consultation_level_id' => ['required', 'numeric', 'exists:consultation_level,id'],
            'act_type_id' => ['required', 'numeric'],
            'legislative_program_id' => ['nullable', 'numeric'],
            'operational_program_id' => ['nullable', 'numeric'],
            'open_from' => ['required', 'date', 'after:today'],
            'open_to' => ['required', 'date', 'after:open_from'],
            'regulatory_act_id' => ['nullable', 'numeric'],
            'pris_act_id' => ['nullable', 'numeric'],
            'act_links' => ['nullable', 'string'],
            'active' => ['boolean', 'nullable'],
            'stay' => ['nullable'],
        ];

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:public_consultation'];
        }

        $availableLanguages = config('available_languages');
        foreach (PublicConsultation::translationFieldsProperties() as $field => $properties) {
            foreach ($availableLanguages as $lang) {
                $rules[$field .'_'. $lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
