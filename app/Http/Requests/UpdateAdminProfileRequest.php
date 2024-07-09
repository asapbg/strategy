<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateAdminProfileRequest extends FormRequest
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
            'first_name'            => ['required', 'string', 'max:255'],
            'last_name'             => ['required', 'string', 'max:255'],
            'middle_name'             => ['nullable', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->user()->id)],
            'password'              => ['nullable', 'confirmed', Password::min(6)->numbers()],
            'password_confirmation' => ['nullable','same:password']
        ];
    }
}
