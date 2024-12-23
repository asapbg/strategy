<?php

namespace App\Http\Requests;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Enums\StrategicDocumentFileEnum;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentFile;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StoreStrategicDocumentRequest extends FormRequest
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
            'id' => ['required', 'numeric'],
            'stay' => ['nullable', 'numeric'],
            'strategic_document_level_id' => ['required', 'numeric', 'in:' . implode(',', array_column(InstitutionCategoryLevelEnum::cases(), 'value'))],
            'policy_area_id' => ['nullable', 'numeric', 'exists:field_of_actions,id'],
            'strategic_document_type_id' => ['required', 'numeric', 'exists:strategic_document_type,id'],
//            'strategic_document_type_file_main_id' => ['required', 'numeric', 'exists:strategic_document_type,id'],
            'strategic_act_type_id' => ['nullable', 'numeric', 'exists:strategic_act_type,id'],
            'accept_act_institution_type_id' => ['required', 'numeric', 'exists:authority_accepting_strategic,id'],
            'public_consultation_id' => ['nullable', 'numeric'],
            'active' => ['nullable', 'numeric', 'in:0,1'],
            //'valid_at_main' => ['required', 'date'],
            'valid_at_main' => ['required_if:date_valid_indefinite_main,0', 'date', 'nullable'],
            'pris_act_id' => ['nullable'],
            'strategic_act_number' => ['nullable', 'string', 'max:100'],
            'strategic_act_link' => ['nullable'],
            'document_date' => ['nullable', 'date'],
            'link_to_monitorstat' => ['nullable', 'string', 'max:1000', 'url', 'regex:/^(https?:\/\/)/'],
            'document_date_accepted' => ['nullable', 'date'],
            'date_expiring_indefinite' => 'required_without:date_expiring|boolean',
            'document_date_expiring' => ['required_if:date_expiring_indefinite,0', 'date', 'nullable'],
            'parent_document_id' => 'sometimes|nullable',
            'ekatte_area_id' => ['nullable', 'numeric', 'exists:field_of_actions,id'],
            'ekatte_municipality_id' => ['nullable', 'numeric'],
            'visible_in_report_main' => ['nullable', 'numeric'],
        ];

        if( request()->input('pris_act_id')) {
            $rules['pris_act_id'][] = 'numeric';
            $rules['pris_act_id'][] = 'exists:pris,id';
            //$rules['pris_act_id'][] = ['exists:pris,id'];
        }
        if( request()->input('strategic_act_link')) {
            $rules['strategic_act_link'][] = 'url';
            $rules['strategic_act_link'][] = 'string';
            $rules['strategic_act_link'][] = 'max:1000';
            $rules['strategic_act_link'][] = 'regex:/^(https?:\/\/)/';
        }

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:strategic_document'];
        }
//        if (request()->isMethod('put') ) {
//            foreach (config('available_languages') as $lang) {
//                $rules['file_strategic_documents_' .$lang['code'] . '_main'] = StrategicDocumentFileEnum::validationRules($lang['code']);
//            }
//        } else {
//            foreach (config('available_languages') as $lang) {
//                $rules['file_strategic_documents_' .$lang['code']] = StrategicDocumentFileEnum::validationRules($lang['code']);
//            }
//        }

        //dd($rules, request()->file('file_strategic_documents_bg_main'));
//        foreach (config('available_languages') as $lang) {
//            foreach (StrategicDocumentFile::translationFieldsPropertiesMain() as $field => $properties) {
//                $rules[$field .'_'. $lang['code']] = $properties['rules'];
//            }
//        }
//        foreach (StrategicDocument::translationFieldsProperties() as $field => $properties) {
//            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
//        }
//        if (request()->has('main_fileId_bg')) {
//            $rules['file_strategic_documents_bg'][] = 'sometimes';
//            $rules['file_strategic_documents_bg_main'][] = 'sometimes';
//        }

        if (request()->get('date_expiring_indefinite') == 1) {
            $rules['document_date_expiring'][] = 'sometimes';
        }

        return $this->getRules($rules, StrategicDocument::translationFieldsProperties());
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'document_date_expiring.required_if' => 'Полето ":attribute" е задължително, когато датата на изтичане не е неограничена.',
            'valid_at_main.required_if' => 'Полето ":attribute" е задължително, когато датата на изтичане не е неограничена.',
        ];
    }
}
