<?php

namespace App\Http\Requests;

use App\Models\Page;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageStoreRequest extends FormRequest
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
            'active' => ['required', 'numeric', 'gt:0'],
            'order_idx' => ['required', 'numeric', 'gte:0'],
            'in_footer' => ['nullable', 'numeric'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('page', 'slug')->ignore((int)request()->input('id'))],
        ];

        if( request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:page'];
        }

        return $this->getRules($rules, Page::translationFieldsProperties());
    }
}
