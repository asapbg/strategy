<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property AdvisoryBoard $item
 */
class StoreAdvisoryBoardMeetingsRequest extends FormRequest
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
            'next_meeting' => 'required|date',
        ];

        return $this->getRules($rules, AdvisoryBoardMeeting::translationFieldsProperties());
    }
}
