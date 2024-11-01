<?php

namespace App\Http\Requests;

use App\Rules\UniqueEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeUserDataRequest extends FormRequest
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
        if(request()->input('edit', 0)){
            return [
                'edit' => ['nullable', 'numeric'],
                'notification_email' => ['required', 'string', 'email', 'max:255'],
            ];
        } else{
            return [
                'edit' => ['nullable', 'numeric'],
                'org_name' => ['nullable', 'string', 'max:255', 'required_without:first_name'],
                'first_name' => ['required', 'string', 'max:255'],
                'middle_name' => ['nullable', 'string'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', new UniqueEmail((int)auth()->user()->id)],
            ];
        }
    }
}
