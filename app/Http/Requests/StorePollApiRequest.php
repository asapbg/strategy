<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePollApiRequest extends FormRequest
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
        $rules = [
            'consultation_id' => ['nullable', 'exists:public_consultation,id'],
            'name' => ['required', 'max:255'],
            'start_date' => ['required', 'date_format:'.config('app.date_format')],
            'end_date' => ['nullable'],
            'is_once' => ['nullable', 'numeric'],
            'only_registered' => ['nullable', 'numeric', 'in:0,1'],
            'status' => ['required', 'numeric', 'in:0,1'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.text' => ['required', 'string'],
            'questions.*.options' => ['required', 'array', 'min:2'],
            'questions.*.options.*' => ['required', 'string', 'min:1'],
        ];

        if( !empty($this->request->get('start_date')) && !empty($this->request->get('end_date')) ) {
            $rules['end_date'] = ['date_format:'.config('app.date_format')];
        }

        if( (int)$this->request->get('id') ) {
            $rules['status'] = ['required', Rule::in([0,1])];
        }

        return $rules;
    }
}
