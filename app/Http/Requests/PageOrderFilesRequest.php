<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageOrderFilesRequest extends FormRequest
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
            'file_id' => ['required', 'array'],
            'file_id.*' => ['required', 'numeric', 'min:1'],
            'ord' => ['required', 'array'],
            'ord.*' => ['required', 'numeric', 'min:1'],
        ];
    }
}
