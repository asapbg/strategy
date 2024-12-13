<?php

namespace App\Http\Requests;

use App\Models\FieldOfAction;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldOfActionRequest extends FormRequest
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
            'parentid' => ['required', 'exists:field_of_actions,id'],
            'icon_class' => ['required', 'string', 'max:255']
        ];

        foreach (config('available_languages') as $lang) {
            foreach (FieldOfAction::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
