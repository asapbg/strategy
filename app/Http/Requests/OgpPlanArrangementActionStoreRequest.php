<?php

namespace App\Http\Requests;

use App\Models\OgpPlanArrangementAction;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class OgpPlanArrangementActionStoreRequest extends FormRequest
{
    use TranslatableFieldsRules;
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'id' => ['required', 'numeric'],
            'ogp_plan_arrangement_id' => ['required', 'numeric'],
            'new_from_date' => ['required', 'date', 'before:new_to_date'],
            'new_to_date' => ['required', 'date'],
        ];

        $translateFields = [];
        $fields = OgpPlanArrangementAction::translationFieldsProperties();
        foreach ($fields as $k => $f){
            $translateFields['new_'.$k] = $f;
        }

        return $this->getRules($rules, $translateFields);
    }
}
