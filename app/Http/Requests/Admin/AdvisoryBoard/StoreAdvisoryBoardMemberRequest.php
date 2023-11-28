<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMember;
use App\Traits\FailedAuthorization;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdvisoryBoardMemberRequest extends FormRequest
{

    use FailedAuthorization;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', AdvisoryBoardMember::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'advisory_board_id' => 'required|integer|exists:advisory_boards,id',
            'advisory_type_id' => 'required|integer',
            'advisory_chairman_type_id' => 'required|integer|exists:advisory_chairman_type,id',
            'consultation_level_id' => 'required|integer|exists:consultation_level,id',
        ];

        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoardMember::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
