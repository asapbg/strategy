<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Enums\AdvisoryTypeEnum;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMember;
use App\Traits\FailedAuthorization;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdvisoryBoardMemberRequest extends FormRequest
{

    use FailedAuthorization, TranslatableFieldsRules;

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
            'advisory_type_id'  => 'required|integer|in:' . rtrim(implode(',', AdvisoryTypeEnum::values()), ','),
            'email'             => 'nullable|email',
            'institution_id'    => 'nullable|integer|exists:institution,id',
            'is_member' => 'nullable|numeric'
        ];

//        foreach (config('available_languages') as $lang) {
//            foreach (AdvisoryBoardMember::translationFieldsProperties() as $field => $properties) {
//                $rules[$field . '_' . $lang['code']] = $properties['rules'];
//            }
//        }

        return $this->getRules($rules, AdvisoryBoardMember::translationFieldsProperties());
    }
}
