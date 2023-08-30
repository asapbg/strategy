<?php

namespace App\Http\Requests;

use App\Models\File;
use App\Models\Publication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'id' => ['required', 'numeric'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('publication', 'slug')->ignore((int)request()->input('id'))],
            'type' => ['required', 'numeric'],
            'publication_category_id' => ['nullable', 'numeric'],
            'published_at' => ['required'],
            'active' => ['required', 'numeric', 'in:0,1'],
            'file' => ['nullable', 'file',  'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', ['jpg', 'jpeg', 'png'])],
        ];

        if( request()->isMethod('put') ) {
            $rules['id'] = ['required', 'numeric', 'exists:publication,id'];
        }

        foreach (config('available_languages') as $lang) {
            foreach (Publication::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
