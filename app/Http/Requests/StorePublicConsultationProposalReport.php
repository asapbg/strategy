<?php

namespace App\Http\Requests;

use App\Enums\DocTypesEnum;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicConsultationProposalReport extends FormRequest
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
        $docType = DocTypesEnum::PC_COMMENTS_REPORT->value;
        $rules = [
            'id' => ['required', 'numeric', 'exists:public_consultation'],
            'message' => ['nullable', "required_without:file_{$docType}_bg", 'string'],
            'report_date' => ['required', 'date'],
            'report_time' => ['required', 'date_format:H:i'],
        ];

        foreach (config('available_languages') as $lang){
            $rules["file_{$docType}_{$lang['code']}"] = DocTypesEnum::validationRules($docType, $lang['code']);
        }

        return $rules;
    }
}
