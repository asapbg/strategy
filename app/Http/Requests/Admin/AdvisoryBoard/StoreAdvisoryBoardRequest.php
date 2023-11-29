<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMember;
use App\Traits\FailedAuthorization;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdvisoryBoardRequest extends FormRequest
{

    use FailedAuthorization;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', AdvisoryBoard::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'policy_area_id' => 'required|integer|exists:policy_area,id',
            'advisory_chairman_type_id' => 'required|integer|exists:advisory_chairman_type,id',
            'advisory_act_type_id' => 'required|integer|exists:advisory_act_type,id',
            'authority_id' => 'required|integer|exists:authority_advisory_board,id',
            'meetings_per_year' => 'required|integer',
        ];

        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoard::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }

            if ($this->request->has('has_vice_chairman')) {
                foreach (AdvisoryBoardMember::translationFieldsProperties() as $field => $properties) {
                    $rules[$field . '_' . $lang['code']] = $properties['rules'];
                }
            }
        }

        return $rules;
    }
}
