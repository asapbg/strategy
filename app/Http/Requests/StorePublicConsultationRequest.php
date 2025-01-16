<?php

namespace App\Http\Requests;

use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Traits\TranslatableFieldsRules;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicConsultationRequest extends FormRequest
{
    use TranslatableFieldsRules;
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
            'active' => ['nullable', 'numeric'],
            'stay' => ['nullable'],
//            'monitorstat' => ['nullable', 'string', 'max:255'],
            'field_of_actions_id' => ['required', 'numeric', 'exists:field_of_actions,id'],
            'law_id' => ['nullable', 'numeric'],
            'pris_id' => ['nullable', 'numeric'],
        ];
        //dd($rules);

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:public_consultation'];
        }

        $availableLanguages = config('available_languages');
        foreach (PublicConsultation::translationFieldsProperties() as $field => $properties) {
            foreach ($availableLanguages as $lang) {
                $rules[$field .'_'. $lang['code']] = $properties['rules'];
                if($field == PublicConsultation::SHORT_REASON_FIELD) {
                    $from = request()->filled('open_from') ? Carbon::parse(request()->input('open_from')) : null;
                    $to = request()->filled('open_to') ? Carbon::parse(request()->input('open_to')) : null;
                    if( $to && $from && $to->diffInDays($from) <= PublicConsultation::SHORT_DURATION_DAYS ) {
                        $rules[$field .'_'. $lang['code']][0] = 'required';
                    }
                }
            }
        }

        if(auth()->user()->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE]) && (!request()->isMethod('put') || $this->item->old_id)) {
            $rules['institution_id'] = ['required', 'numeric', 'exists:institution,id'];
        }

//        return $rules;
        return $this->getRules($rules, PublicConsultation::translationFieldsProperties());
    }
}
