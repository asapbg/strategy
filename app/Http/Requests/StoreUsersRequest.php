<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUsersRequest extends FormRequest
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
        $must_change_password = $this->offsetGet('must_change_password');
        $rules = [
//            'is_org'                => ['required', 'boolean'],
            'username'              => ['required', 'unique:users', 'string', 'max:255'],
            'first_name'            => ['required', 'string', 'max:255'],
            'last_name'             => ['required_if:is_org,0', 'string', 'max:255'],
            'email'                 => ['nullable', 'string', 'email', 'max:255'],
            'roles'                 => ['required' ,'array', 'min:1'],
            'user_type'             => ['required' ,'numeric'],
        ];

        if( request()->input('id') ) {
            $rules['email'][] = Rule::unique('users', 'email')->ignore((int)request()->input('id'));
        } else {
            $rules['email'][] = 'unique:users,email';
        }

        $roles = request()->input('roles');
        if( $roles && count(array_intersect(rolesNames($roles), User::ROLES_WITH_INSTITUTION)) != 0 ) {
            $rules['institution_id'] = ['required', 'numeric', 'exists:institution,id'];
        }

        if(!$must_change_password) {
            $rules = array_merge($rules, self::passwordRequestValidationRules());
        }

        return $rules;
    }

    /**
     * User password validation rules
     *
     * @return array
     */
    public static function passwordRequestValidationRules()
    {
        return [
            'password'              => ['required', 'confirmed',
                Password::min(6)->numbers()->letters()->symbols()],
            'password_confirmation' => ['required','same:password']
        ];
    }
}
