<?php

namespace App\Rules;

use App\Models\Pris;
use Carbon\Carbon;
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
    public function __construct($legalActTypeId, $docDate, $ignoreId = 0)
    {
        $this->actType = $legalActTypeId;
        $this->from = Carbon::parse($docDate.' 00:00:00')->startOfYear()->format('Y-m-d');
        $this->to = Carbon::parse($docDate.' 00:00:00')->endOfYear()->format('Y-m-d');
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
        $q = Pris::LastVersion()->where('legal_act_type_id', (int)$this->actType)
            ->where('doc_num', '=', (int)$value)
            ->where(function ($q){
                $q->where('doc_date', '>=', $this->from)
                    ->where('doc_date', '<=', $this->to);
            });
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
