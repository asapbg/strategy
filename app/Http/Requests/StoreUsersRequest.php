<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\UniqueEmail;
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
            //'username'              => ['required', 'unique:users', 'string', 'max:255'],
            'first_name'            => ['required', 'string', 'max:255'],
            'last_name'             => ['required_if:is_org,0', 'string', 'max:255'],
            'middle_name'             => ['nullable', 'string', 'max:255'],
            'email'                 => ['nullable', 'string', 'email', 'max:255'],
            'user_type'             => ['required' ,'numeric'],
            'active'             => ['required' ,'numeric'],
        ];

        if(request()->input('sd', 0)){
            if( !request()->input('id', 0) ) {
                $rules['roles'] = ['required' ,'array', 'min:1'];
            } else{
                $rules['roles'] = ['nullable', 'array'];
            }
            $rules['institution_id'] = ['required' ,'numeric'];
        } else{

            $rules['institution_id'] = ['nullable' ,'numeric'];
            $rules['roles'] = ['required' ,'array', 'min:1'];
        }

        if( request()->input('id') ) {
            $rules['activity_status'] = ['required', 'numeric'];
            if( (int)request()->input('user_type') == User::USER_TYPE_EXTERNAL ) {
                $rules['notification_email'] = ['required', 'email'];
            }

            $rules['email'][] = new UniqueEmail((int)request()->input('id'));
        } else {
            $rules['email'][] = new UniqueEmail();
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
