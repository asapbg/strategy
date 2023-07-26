<?php

namespace App\Http\Requests;

use App\Models\PCSubject;
use Illuminate\Foundation\Http\FormRequest;

class StorePCSubjectRequest extends FormRequest
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
            'type' => ['required', 'numeric'],
            'eik' => ['required', 'numeric'],
            'contract_date' => ['required', 'date'],
            'price' => ['required', 'numeric'],
        ];

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:strategic_document'];
        }

        foreach (PCSubject::translationFieldsProperties() as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }

        return $rules;
    }
}
