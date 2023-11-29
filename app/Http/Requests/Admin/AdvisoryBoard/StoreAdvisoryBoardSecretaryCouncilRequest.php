<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoardSecretaryCouncil;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdvisoryBoardSecretaryCouncilRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', AdvisoryBoardSecretaryCouncil::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'advisory_board_id' => 'required|integer|exists:advisory_boards,id'
        ];

        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoardSecretaryCouncil::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
