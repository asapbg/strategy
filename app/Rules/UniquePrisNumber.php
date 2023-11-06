<?php

namespace App\Rules;

use App\Models\Pris;
use Illuminate\Contracts\Validation\Rule;

class UniquePrisNumber implements Rule
{
    private $actType;
    private $ignoreId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($legalActTypeId, $ignoreId = 0)
    {
        $this->actType = $legalActTypeId;
        $this->ignoreId = (int)$ignoreId;
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
        $q = Pris::where('legal_act_type_id', $this->actType)->where('doc_num', '=', $value);
        if( $this->ignoreId ) {
            $q->where('id', '<>', $this->ignoreId);
        }
        $exist = $q->first();
        if( $exist ) {
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
        return trans('validation.custom.pris_unique_doc_num');
    }
}
