<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvBoardStoreMessage extends FormRequest
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
            'recipient' => ['required_without:send_to_all', 'array'],
            'recipient.*' => ['required_without:send_to_all', 'numeric', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'send_to_all' => ['nullable', 'numeric'],
        ];
    }
}
