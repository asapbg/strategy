<?php

namespace App\Http\Requests;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Executor;
use Illuminate\Foundation\Http\FormRequest;

class StoreExecutorRequest extends FormRequest
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
    public function rules()
    {
        $rules = [
            'eik'                           => ['nullable', 'numeric'],
            'institution_id'                => ['required', 'numeric', 'exists:institution,id'],
            'contract_date'                 => ['required', 'date'],
            'price'                         => ['required', 'numeric', 'between:0,99999999.99'],
        ];

        foreach (Executor::TRANSLATABLE_FIELDS as $field) {
            foreach (AdminController::getLanguages() as $lang) {
                $condition = ($lang['default']) ? 'required' : 'nullable';
                if (in_array($field,["hyperlink","contractor_name"])) {
                    $condition = 'nullable';
                }
                $rules[$field."_".$lang['code']] = [$condition, 'string', 'min:3'];
            }
        }

        return $rules;

    }
}
