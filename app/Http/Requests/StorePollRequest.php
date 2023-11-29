<?php

namespace App\Http\Requests;

use App\Models\Poll;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePollRequest extends FormRequest
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
            'id' => ['sometimes', 'numeric'],
            'pc' => ['nullable'],
            'name' => ['required', 'max:255'],
            'start_date' => ['required', 'date_format:'.config('app.date_format')],
            'end_date' => ['nullable', 'date_format:'.config('app.date_format')],
            'is_once' => ['nullable', 'numeric'],
            'only_registered' => ['nullable', 'numeric'],
            'stay' => ['nullable', 'numeric'],
            'status' => ['required', 'numeric', 'in:0,1'],
            'save_to_pc' => ['nullable', 'numeric']
        ];

        if( !empty($this->request->get('start_date')) && !empty($this->request->get('end_date')) ) {
            $rules['end_date'] = ['date_format:'.config('app.date_format')];
        }

        if( (int)$this->request->get('id') ) {
            $rules['status'] = ['required', Rule::in([0,1])];
        }

        return $rules;
    }
}
