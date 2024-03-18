<?php

namespace App\Http\Requests;

use App\Models\ActType;
use App\Models\OgpArea;
use App\Models\OgpPlan;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class OgpPlanRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        $id = $this->request->get('id', 0);

        if(request()->get('save_dev_plan')){
            return [
                'save_dev_plan' => ['nullable', 'numeric'],
                'develop_plan_id' => ['nullable', 'numeric'],
                'self_evaluation_published_at' => ['nullable', 'date'],
            ];

        } elseif(request()->get('save_status')){

            return [
                'save_status' => ['nullable', 'numeric'],
                'status' => ['nullable', 'numeric'],
                'ogp_status_id' => ['nullable', 'numeric'],
            ];

        } else {

            $rules = [
                'from_date' => ['required', 'date', 'before:to_date'],
                'to_date' => ['required', 'date'],
                'active' => ['required', 'numeric'],
                'status' => ['nullable', 'numeric'],
                'ogp_status_id' => ['nullable', 'numeric'],
                'ogp_area' => ['nullable', 'numeric', 'exists:ogp_area,id'],
                'develop_plan_id' => ['nullable', 'numeric'],
                'self_evaluation_published_at' => ['nullable', 'date'],
            ];

            $fields = array();
            foreach (OgpPlan::translationFieldsProperties() as $key => $v){
                if(!in_array($key, ['report_title', 'report_content'])){
                    $fields[$key] = $v;
                }
            }

            return $this->getRules($rules, $fields);
        }
    }
}
