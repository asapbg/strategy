<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoardSecretaryCouncil;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $item - AdvisoryBoard
 */
class UpdateAdvisoryBoardSecretaryCouncilRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update',
            [
                AdvisoryBoardSecretaryCouncil::class,
                AdvisoryBoardSecretaryCouncil::find($this->request->get('advisory_board_secretary_council_id', 0))
            ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'advisory_board_secretary_council_id' => 'required|integer|exists:advisory_board_secretary_councils,id'
        ];

        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoardSecretaryCouncil::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
