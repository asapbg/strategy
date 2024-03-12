<?php

namespace App\Http\Requests;

use App\Models\OgpPlan;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class OgpPlanReportRequest extends FormRequest
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
            'plan' => ['required', 'numeric']
        ];

        $fields = array();
        foreach (OgpPlan::translationFieldsProperties() as $key => $v){
            if(in_array($key, ['report_title', 'report_content'])){
                $fields[$key] = $v;
            }
        }

        return $this->getRules($rules, $fields);
    }
}
