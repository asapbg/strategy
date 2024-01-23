<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoardSecretariat;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdvisoryBoardSecretariatRequest extends FormRequest
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
        $rules = [];

        return $this->getRules($rules, AdvisoryBoardSecretariat::translationFieldsProperties());
    }
}
