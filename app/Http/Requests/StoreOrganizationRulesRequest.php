<?php

namespace App\Http\Requests;

use App\Models\AdvisoryBoardOrganizationRule;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRulesRequest extends FormRequest
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
        $rules = [];

        return $this->getRules($rules, AdvisoryBoardOrganizationRule::translationFieldsProperties());
    }
}
