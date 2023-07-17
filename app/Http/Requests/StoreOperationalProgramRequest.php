<?php

namespace App\Http\Requests;

use App\Models\Consultations\OperationalProgram;
use Illuminate\Foundation\Http\FormRequest;

class StoreOperationalProgramRequest extends FormRequest
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
            'effective_from' => ['required', 'date'],
            'effective_to' => ['required', 'date'],
        ];

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:institution'];
        }

        foreach (OperationalProgram::translationFieldsProperties() as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }

        return $rules;
    }
}
