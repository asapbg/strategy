<?php

namespace App\Http\Requests;

use App\Models\ActType;
use App\Models\OgpArea;
use App\Models\OgpPlan;
use Illuminate\Foundation\Http\FormRequest;

class OgpPlanRequest extends FormRequest
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
        $id = $this->request->get('id', 0);

        $rules = [
            'from_date' => 'required',
            'to_date' => 'required',
            'active' => ['required', 'numeric'],
        ];
        if($id == 0) {
            $rules['ogp_area'] = 'required|gt:0';
        }

        foreach (config('available_languages') as $lang) {
            foreach (OgpPlan::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
