<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeetingDecision;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property AdvisoryBoard $item
 */
class StoreAdvisoryBoardMeetingDecisionRequest extends FormRequest
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
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'advisory_board_meeting_id' => 'required|integer|exists:advisory_board_meetings,id',
            'date_of_meeting' => 'required|date',
            'agenda' => 'nullable|string',
            'protocol' => 'nullable|string',
        ];

        return $this->getRules($rules, AdvisoryBoardMeetingDecision::translationFieldsProperties());
    }
}
