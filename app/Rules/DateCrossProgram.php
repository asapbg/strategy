<?php

namespace App\Rules;

use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\OperationalProgram;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class DateCrossProgram implements Rule
{
    private $programType;
    private $from;
    private $start;
    private $end;
    private $ignoreId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($from = true, $programType = 'operational', $ignoreId = 0)
    {
        $this->programType = $programType;
        $this->from = $from;
        $this->ignoreId = $ignoreId;
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
        if( empty($value) ) {
            return true;
        }

        $date = Carbon::parse('01.'.$value);
        if($this->from) {
            $date = $date->format('Y-m-d');
        } else {
            $date = $date->endOfMonth()->format('Y-m-d');
        }
        if( $this->programType == 'operational' ) {
            $crossing = OperationalProgram::where('from_date', '<=', $date)
                ->where('to_date', '>=', $date)
                ->where('id', '<>', (int)$this->ignoreId)
                ->first();
        } else {
            $crossing = LegislativeProgram::where('from_date', '<=', $date)
                ->where('to_date', '>=', $date)
                ->where('id', '<>', (int)$this->ignoreId)
                ->first();
        }

        if($crossing) {
            $this->start = $crossing->from_date;
            $this->end = $crossing->to_date;
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
        return trans('validation.date_cross_program', ['start' => displayDate($this->start), 'end' => displayDate($this->end)]);
    }
}
