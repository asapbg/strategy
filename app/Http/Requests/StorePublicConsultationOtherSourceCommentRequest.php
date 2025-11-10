<?php

namespace App\Http\Requests;

use App\Enums\DocTypesEnum;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicConsultationOtherSourceCommentRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $doc_type = DocTypesEnum::PC_OTHER_SOURCE_COMMENTS->value;
        $rules = [
            'id' => ['required']
        ];

        foreach (config('available_languages') as $lang){
            $rules["file_{$doc_type}_{$lang['code']}"] = DocTypesEnum::validationRules($doc_type, $lang['code']);
            $rules["filename_{$doc_type}_{$lang['code']}"] = ['required', 'string'];
            $rules["file_source_{$lang['code']}"] = ['required', 'string'];
        }

        return $rules;
    }
}
