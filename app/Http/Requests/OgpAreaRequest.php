<?php

namespace App\Http\Requests;

use App\Models\ActType;
use App\Models\OgpArea;
use Illuminate\Foundation\Http\FormRequest;

class OgpAreaRequest extends FormRequest
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
    public function rules(): array
    {
        $rules = [
            'from_date' => 'required',
            'to_date' => 'required',
            'status_id' => 'required|gt:0',
            'active' => '',
        ];

        foreach (config('available_languages') as $lang) {
            foreach (OgpArea::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
