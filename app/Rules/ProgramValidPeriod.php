<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ProgramValidPeriod implements Rule
{
    private $toDate;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($toDate)
    {
        $this->toDate = !empty($toDate) ? Carbon::parse('01.'.$toDate)->endOfMonth()->format('Y-m-d') : null;
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
        if( !is_null($this->toDate) ) {
            $today = Carbon::now()->format('Y-m-d');
            if( $today > $this->toDate ) {
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
        return trans('validation.program_valid_period');
    }
}
