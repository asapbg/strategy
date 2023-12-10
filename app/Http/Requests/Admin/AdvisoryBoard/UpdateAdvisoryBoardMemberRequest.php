<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Enums\AdvisoryTypeEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMember;
use App\Traits\FailedAuthorization;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvisoryBoardMemberRequest extends FormRequest
{

    use FailedAuthorization;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', [AdvisoryBoardMember::class, AdvisoryBoardMember::find($this->request->get('advisory_board_member_id', 0))]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'advisory_board_member_id'  => 'required|integer|exists:advisory_board_members,id',
            'advisory_board_id'         => 'required|integer|exists:advisory_boards,id',
            'advisory_type_id'          => 'required|integer|in:' . rtrim(implode(',', AdvisoryTypeEnum::values()), ','),
            'email'                     => 'nullable|email',
        ];

        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoardMember::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
