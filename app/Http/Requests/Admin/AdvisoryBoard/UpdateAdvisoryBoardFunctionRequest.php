<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunction;
use App\Rules\UniqueYearWorkProgram;
use App\Traits\FailedAuthorization;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $item
 */
class UpdateAdvisoryBoardFunctionRequest extends FormRequest
{

    use TranslatableFieldsRules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
        //return $this->user()->can('create', AdvisoryBoard::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'adv_board_id' => 'required|integer|exists:advisory_boards,id',
            'function_id' => 'required|integer|exists:advisory_board_functions,id',
            'working_year' => ['required','date_format:Y', new UniqueYearWorkProgram(request()->input('adv_board_id'), request()->input('function_id'))]
        ];

        return $this->getRules($rules, AdvisoryBoardFunction::translationFieldsProperties());
    }
}
