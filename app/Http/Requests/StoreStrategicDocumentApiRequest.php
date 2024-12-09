<?php

namespace App\Http\Requests;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\StrategicDocument;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreStrategicDocumentApiRequest extends FormRequest
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
            'strategic_document_level_id' => ['required', 'numeric', 'in:' . implode(',', array_column(InstitutionCategoryLevelEnum::cases(), 'value'))],
            'policy_area_id' => ['nullable', 'numeric', 'exists:field_of_actions,id'],
            'strategic_document_type_id' => ['required', 'numeric', 'exists:strategic_document_type,id'],
            'accept_act_institution_type_id' => ['required', 'numeric', 'exists:authority_accepting_strategic,id'],
            'public_consultation_id' => ['nullable', 'numeric', 'exists:public_consultation,id'],
            'active' => ['required', 'numeric', 'in:0,1'],
            'pris_act_id' => ['nullable', 'numeric', 'exists:pris,id'],
            'strategic_act_number' => ['nullable', 'string', 'max:100'],
            'strategic_act_link' => ['nullable'],
            'document_date' => ['nullable', 'date'],
            'link_to_monitorstat' => ['nullable', 'string', 'max:1000', 'url', 'regex:/^(https?:\/\/)/'],
            'document_date_accepted' => ['nullable', 'date'],
            'date_expiring_indefinite' => 'required_without:date_expiring|numeric|in:0,1',
            'document_date_expiring' => ['required_if:date_expiring_indefinite,0', 'date', 'nullable'],
            'parent_document_id' => 'sometimes|nullable|exists:strategic_document,id'
        ];

        if( $this->request->get('strategic_act_link')) {
            $rules['strategic_act_link'][] = 'url';
            $rules['strategic_act_link'][] = 'string';
            $rules['strategic_act_link'][] = 'max:1000';
            $rules['strategic_act_link'][] = 'regex:/^(https?:\/\/)/';
        }

        if ($this->request->get('date_expiring_indefinite') == 1) {
            $rules['document_date_expiring'][] = 'sometimes';
        }

        return $this->getRules($rules, StrategicDocument::translationFieldsProperties());
    }
}
