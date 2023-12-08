<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * @property AdvisoryBoard $item
 */
class StoreUserModeratorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->item);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'unique:users', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed',
                Password::min(6)->numbers()->letters()->symbols()],
            'password_confirmation' => ['required', 'same:password'],
        ];
    }
}
