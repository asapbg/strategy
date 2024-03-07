<?php

namespace App\Http\Requests;

use App\Models\OgpPlanArrangement;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class OgpPlanArrangementEvaluationRequest extends FormRequest
{
    use TranslatableFieldsRules;
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
            'id' => ['required', 'numeric'],
        ];
        $translatableFields = [];
        $translatable = OgpPlanArrangement::translationFieldsProperties();
        foreach ($translatable as $k => $t) {
            if(in_array($k, ['evaluation', 'evaluation_status'])){
                $translatableFields[$k] = $t;
            }
        }
        return $this->getRules($rules, $translatableFields);

    }
}
