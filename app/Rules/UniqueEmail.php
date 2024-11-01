<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueEmail implements Rule
{
    private $email;
    private $exludeId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($exludeId = 0)
    {
        $this->exludeId = $exludeId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->email = $value;
        $q = User::where('email', '=', strtolower($this->email));
        if($this->exludeId){
            $q->where('id', '<>', $this->exludeId);
        }

        if($q->count()){
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.email_exists', ['email' => $this->email]);
    }
}
