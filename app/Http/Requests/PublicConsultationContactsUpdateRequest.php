<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicConsultationContactsUpdateRequest extends FormRequest
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
            'pc_id' => ['required', 'numeric', 'exists:public_consultation,id'],
            'id' => ['required', 'array'],
            'id.*' => ['required', 'numeric', 'exists:public_consultation_contact,id'],
            'name' => ['required', 'array'],
            'name.*' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'array'],
            'email.*' => ['required', 'email', 'max:255', 'min:3']
        ];
    }
}
