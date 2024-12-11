<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\User;
use App\Rules\UniqueEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * @property AdvisoryBoard $item
 * @property User          $user
 */
class UpdateAdvisoryBoardModeratorRequest extends FormRequest
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
            'institution_id'        => ['nullable', 'integer', 'exists:institution,id'],
            'first_name'            => ['required', 'string', 'max:255'],
            'middle_name'           => ['nullable', 'string'],
            'last_name'             => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', new UniqueEmail($this->user->id)],
            'password'              => ['nullable', 'confirmed', Password::min(6)->numbers()->letters()->symbols()],
            'password_confirmation' => ['nullable', 'same:password'],
            'job'                   => ['nullable', 'string', 'max:255'],
            'unit'                  => ['nullable', 'string', 'max:255'],
            'phone'                 => ['nullable', 'string', 'max:255'],
        ];
    }
}
