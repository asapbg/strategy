<?php

namespace App\Http\Requests;

use App\Models\Pris;
use App\Rules\UniquePrisNumber;
use Illuminate\Foundation\Http\FormRequest;

class PrisStoreRequest extends FormRequest
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
            'doc_num' => ['required', 'string', new UniquePrisNumber(request()->input('legal_act_type_id'), request()->input('doc_date'))],
            'doc_date' => ['required', 'date'],
            'legal_act_type_id' => ['required', 'numeric', 'exists:legal_act_type,id'],
            'institution_id' => ['required', 'numeric', 'exists:institution,id'],
            'protocol' => ['required', 'string'],
            'public_consultation_id' => ['nullable', 'numeric'],
            'newspaper_number' => ['nullable', 'numeric'],
            'newspaper_year' => ['nullable', 'date_format:Y', 'max:4'],
            'tags' => ['array'],
            'tags.*' => ['required', 'exists:tag,id'],
            'publish' => ['nullable', 'numeric'],
        ];

        if( request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:pris,id'];
            $rules['doc_num'] = ['required', 'numeric', new UniquePrisNumber(request()->input('legal_act_type_id'), request()->input('doc_date'), request()->input('id'))];
        }

        foreach (config('available_languages') as $lang) {
            foreach (Pris::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
