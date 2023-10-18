<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionEditRequest extends FormRequest
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
            'question_id' => ['required', 'numeric'],
            'question_name' => ['required', 'string', 'max:255'],
            'answer_name' => ['required', 'array', 'min:2'],
            'answer_name.*' => ['required', 'string', 'max:255'],
            'answer_id' => ['required', 'array', 'min:2'],
            'answer_id.*' => ['required', 'numeric'],
        ];
    }
}
