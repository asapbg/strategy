<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MulticriteriaWeightSum implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if(!empty(request()->input('weight')) && is_array(request()->input('weight'))){
            $sum = array_sum(request()->input('weight'));
            if((int)$sum != 100){
                return false;
            }
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
        return trans('validation.multicriteria_weight_sum');
    }
}
