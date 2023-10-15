<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicConsultationContactStoreRequest extends FormRequest
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
            'pc_id' => ['nullable', 'numeric', 'exists:public_consultation,id'],
            'new_name' => ['required', 'string', 'max:255'],
            'new_email' => ['required', 'email', 'max:255'],
        ];
    }
}
