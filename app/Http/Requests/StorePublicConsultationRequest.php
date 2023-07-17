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
            'consultation_type_id' => ['required', 'numeric'],
            'consultation_level_id' => ['required', 'numeric'],
            'act_type_id' => ['required', 'numeric'],
            'program_project_id' => ['required', 'numeric'],
            'link_category_id' => ['required', 'numeric'],
            'open_from' => ['required'],
            'open_to' => ['required'],
            'address' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'active' => ['boolean', 'nullable'],
        ];

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:public_consultation'];
        }

        foreach (PublicConsultation::translationFieldsProperties() as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }

        return $rules;
    }
}
