<?php

namespace App\Http\Requests\Admin\AdvisoryBoardMember;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMember;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property AdvisoryBoardMember $member
 */
class RestoreAdvisoryBoardMemberRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('restore', AdvisoryBoard::find($this->member->advisory_board_id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
