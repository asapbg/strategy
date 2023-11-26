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
    private $t;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($t, $from = true, $programType = 'operational', $ignoreId = 0)
    {
        $this->programType = $programType;
        $this->from = $from;
        $this->ignoreId = $ignoreId;
        $this->t = $t;
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
        $dateT = Carbon::parse('01.'.$this->t);

        if($this->from) {
            $date = $date->format('Y-m-d');
            $dateT = $dateT->endOfMonth()->format('Y-m-d');
        } else {
            $date = $date->endOfMonth()->format('Y-m-d');
            $dateT = $dateT->format('Y-m-d');
        }

        if( $this->programType == 'operational' ) {
            $query = OperationalProgram::where('id', '<>', (int)$this->ignoreId);
        } else {
            $query = LegislativeProgram::where('id', '<>', (int)$this->ignoreId);
        }

        $crossing = $query->where(function ($q) use($date, $dateT){
            $q->where(function ($q) use($date, $dateT){
                $q->where(function ($q) use($date){
                    $q->where('from_date', '<=', $date)
                        ->where('to_date', '>=', $date);
                })->orWhere(function ($q) use($dateT){
                    $q->where('from_date', '<=', $dateT)
                        ->where('to_date', '>=', $dateT);
                });
            })->orWhere(function ($q) use($date, $dateT){
                $q->where('from_date', '>=', $date)
                    ->where('to_date', '<=', $dateT);
            });
        })->first();

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
