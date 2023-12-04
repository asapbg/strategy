<?php

namespace App\Http\Requests;

use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
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
//            'consultation_level_id' => ['required', 'numeric', 'exists:consultation_level,id'],
            'act_type_id' => ['required', 'numeric'],
            'legislative_program_id' => ['nullable', 'required_with:legislative_program_row_id', 'numeric'],
            'legislative_program_row_id' => ['nullable', 'numeric'],
            'operational_program_id' => ['nullable', 'required_with:operational_program_row_id', 'numeric'],
            'operational_program_row_id' => ['nullable', 'numeric'],
            'open_from' => ['required', 'date'],
            'open_to' => ['required', 'date', 'after:open_from'],
            //'regulatory_act_id' => ['nullable', 'numeric'],
            'pris_act_id' => ['nullable', 'numeric'],
            //'act_links' => ['nullable', 'string'],
            'connected_pc' => ['array'],
            'connected_pc.*' => ['numeric', 'exists:public_consultation,id'],
            'active' => ['boolean', 'nullable'],
            'stay' => ['nullable'],
//            'monitorstat' => ['nullable', 'string', 'max:255'],
            'field_of_actions_id' => ['required', 'numeric', 'exists:field_of_actions,id'],
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

        if(auth()->user()->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE]) && !request()->isMethod('put')) {
            $rules['institution_id'] = ['required', 'numeric', 'exists:institution,id'];
        }

        return $rules;
    }
}
