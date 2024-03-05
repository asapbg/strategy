<?php

namespace App\Http\Requests;

use App\Models\OgpArea;
use App\Models\OgpPlanArrangement;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

class OgpPlanArrangementRequest extends FormRequest
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
            'from_date' => ['required', 'date', 'before:to_date'],
            'to_date' => ['required', 'date'],
        ];
        return $this->getRules($rules, OgpPlanArrangement::translationFieldsProperties());

    }
}
