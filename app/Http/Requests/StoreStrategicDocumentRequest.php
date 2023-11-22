<?php

namespace App\Http\Requests;

use App\Enums\StrategicDocumentFileEnum;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

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
            'id' => ['required', 'numeric'],
            'stay' => ['nullable'],
            'strategic_document_level_id' => ['required', 'numeric', 'exists:strategic_document_level,id'],
            'policy_area_id' => ['required', 'numeric', 'exists:policy_area,id'],
            'strategic_document_type_id' => ['required', 'numeric', 'exists:strategic_document_type,id'],
            'strategic_act_type_id' => ['required', 'numeric', 'exists:strategic_act_type,id'],
            'accept_act_institution_type_id' => ['required', 'numeric', 'exists:authority_accepting_strategic,id'],
            'public_consultation_id' => ['required', 'numeric', 'exists:public_consultation,id'],
            //'document_date' => ['required', 'date'],
            'active' => ['required', 'numeric', 'in:0,1'],
            'valid_at' => ['required', 'date'],
            'strategic_act_number' => ['nullable', 'string', 'max:100'],
            'strategic_act_link' => ['nullable', 'string', 'max:1000', 'url', 'regex:/^(https?:\/\/)/'],
            'link_to_monitorstat' => ['nullable', 'string', 'max:1000', 'url', 'regex:/^(https?:\/\/)/'],
            'pris_act_id' => ['nullable', 'numeric'],
            'document_date_accepted' => 'required|date',
            'document_date_expiring' => 'required|date',
        ];

        if( request()->input('pris_act_id')) {
            //$rules['pris_act_id'][] = ['exists:pris,id'];
        }

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:strategic_document'];
        }

        foreach (config('available_languages') as $lang) {
            $rules['file_strategic_documents_' .$lang['code']] = StrategicDocumentFileEnum::validationRules($lang['code']);
        }

        foreach (config('available_languages') as $lang) {
            foreach (StrategicDocumentFile::translationFieldsProperties() as $field => $properties) {
                $rules[$field .'_'. $lang['code']] = $properties['rules'];
            }
        }

        foreach (StrategicDocument::translationFieldsProperties() as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }
        if (request()->has('main_fileId_bg')) {
            $rules['file_strategic_documents_bg'][] = 'sometimes';
        }

        return $rules;
    }
}
