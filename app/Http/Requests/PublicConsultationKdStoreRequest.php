<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicConsultationKdStoreRequest extends FormRequest
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
        return [
            'id' => ['required', 'numeric', 'exists:public_consultation,id'],
            'row_id' => ['required', 'array', 'min:1'],
            'row_id.*' => ['required', 'numeric'],
            'val' => ['required', 'array'],
            'val.*' => ['required', 'string', 'min:1'],
            'stay' => ['nullable', 'numeric'],
        ];
    }
}
