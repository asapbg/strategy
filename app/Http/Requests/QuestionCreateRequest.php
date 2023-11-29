<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionCreateRequest extends FormRequest
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
        return [
            'poll_id' => ['required', 'numeric'],
            'new_question_name' => ['required', 'string', 'max:255'],
            'new_answers' => ['required', 'array', 'min:2'],
            'new_answers.*' => ['required', 'string', 'max:255'],
            'pc' => ['nullable', 'exists:public_consultation,id'],
        ];
    }
}
