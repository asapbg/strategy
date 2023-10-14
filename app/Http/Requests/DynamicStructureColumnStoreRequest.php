<?php

namespace App\Http\Requests;

use App\Models\DynamicStructureColumn;
use Illuminate\Foundation\Http\FormRequest;

class DynamicStructureColumnStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'type' => ['required', 'string', 'in:text,number'],
            'id' => ['required', 'numeric', 'exists:dynamic_structure,id'],
            'in_group' => ['nullable'],
        ];

        foreach (config('available_languages') as $lang) {
            foreach (DynamicStructureColumn::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
