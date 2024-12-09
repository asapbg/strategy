<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard\AdvisoryBoardNomenclatureFieldOfAction;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvisoryBoardNomenclatureFieldOfActionRequest extends FormRequest
{

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
            'icon_class' => ['required', 'string', 'max:255']
        ];

        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoardNomenclatureFieldOfAction::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
