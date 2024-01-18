<?php

namespace App\Rules;

use App\Models\AdvisoryBoardFunction;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class UniqueYearWorkProgram implements Rule
{
    private $programId;
    private $advBoardId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $advBoardId, int|null $programId = null)
    {
        $this->programId = $programId;
        $this->advBoardId = $advBoardId;
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
        $q = AdvisoryBoardFunction::query();
        if($this->programId){
            $q->where('id', '<>', $this->programId);
        }
        $exist = $q->where('advisory_board_id', '=', $this->advBoardId)
            ->where('working_year', '=', $value.'-01-01 00:00:00')
            ->first();

        if($exist) {
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
        return __('validation.unique_year_work_program');
    }
}
