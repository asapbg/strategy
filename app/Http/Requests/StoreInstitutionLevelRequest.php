<?php

namespace App\Http\Requests;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\InstitutionLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInstitutionLevelRequest extends FormRequest
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
        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:institution_level'];
            $rules['nomenclature_level'] = ['required', 'numeric', 'in:'.implode(',', InstitutionCategoryLevelEnum::values())];
        }

        foreach (config('available_languages') as $lang) {
            foreach (InstitutionLevel::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
