<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{

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
            'name_bg' => 'required|string',
            'name_en' => 'required|string',
            'field_of_action_id' => [
                'required',
                Rule::exists((new Report())->getTable())
            ],
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ];
        return $rules;
        dd($rules);
    }
}
