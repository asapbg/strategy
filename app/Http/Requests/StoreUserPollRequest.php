<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserPollRequest extends FormRequest
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
            'id' => ['required', 'numeric', 'exists:poll'],
            'q' => ['required', 'array', 'min:1'],
            'q.*' => ['required', 'numeric', 'exists:poll_question,id']
        ];

        foreach (request()->input('q') as $q){
            $rules['a_'.$q] = ['required', 'array', 'min:1'];
            $rules['a_'.$q.'.*'] = ['required', 'numeric', 'exists:poll_question_option,id'];
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        foreach (request()->input('q') as $q){
            $messages['a_'.$q.'.required'] = trans('validation.custom.a.required');
        }
        return $messages;
    }
}
