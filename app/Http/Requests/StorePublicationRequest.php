<?php

namespace App\Http\Requests;

use App\Models\Publication;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicationRequest extends FormRequest
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
            'type' => ['required', 'numeric'],
            'publication_category_id' => ['required', 'numeric'],
            'event_date' => ['required', 'date'],
            'highlighted' => ['boolean'],
            'active' => ['boolean'],
        ];

        if (request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:strategic_document'];
        }

        foreach (Publication::translationFieldsProperties() as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }

        return $rules;
    }
}
