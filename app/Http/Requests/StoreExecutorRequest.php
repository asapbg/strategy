<?php

namespace App\Http\Requests;

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
        return [
            'contractor_name.bg'            => ['required', 'string', 'max:255'],
            'executor_name.bg'              => ['required', 'string', 'max:255'],
            'contract_subject.bg'           => ['required', 'string'],
            'services_description.bg'       => ['required', 'string'],
            'contract_date'                 => ['required', 'date'],
            'price'                         => ['required', 'numeric', 'between:0,99999999.99'],
        ];
    }
}
