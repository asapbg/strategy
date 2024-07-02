<?php

namespace App\Http\Requests;

use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Traits\TranslatableFieldsRules;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicConsultationApiRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'act_type_id' => ['required', 'numeric', 'exists:act_type,id'],
            'legislative_program_id' => ['nullable', 'required_with:legislative_program_row_id', 'numeric', 'exists:legislative_program,id'],
            'legislative_program_row_id' => ['nullable', 'numeric', 'exists:legislative_program_row,id'],
            'operational_program_id' => ['nullable', 'required_with:operational_program_row_id', 'numeric', 'exists:operational_program,id'],
            'operational_program_row_id' => ['nullable', 'numeric', 'exists:operational_program_row,id'],
            'open_from' => ['required', 'date'],
            'open_to' => ['required', 'date'],
            'connected_pc' => ['array'],
            'connected_pc.*' => ['numeric', 'exists:public_consultation,id'],
            'active' => ['nullable', 'numeric', 'in:0,1'],
            'field_of_actions_id' => ['required', 'numeric', 'exists:field_of_actions,id'],
            'law_id' => ['nullable', 'numeric', 'exists:law,id'],
            'pris_id' => ['nullable', 'numeric', 'exists:pris,id'],
            'institution_id' => ['required', 'numeric', 'exists:institution,id']
        ];

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

        return $this->getRules($rules, PublicConsultation::translationFieldsProperties());
    }
}
