<?php

namespace App\Http\Requests;

use App\Models\AdvisoryBoardCustom;
use App\Models\CustomRole;
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
            'stay' => ['nullable', 'numeric'],
            'stay_in_files' => ['nullable', 'numeric'],
            'save_files' => ['nullable', 'numeric'],
            'publication_category_id' => ['nullable', 'numeric'],
            'published_at' => ['required'],
            'active' => ['required', 'numeric', 'in:0,1'],
            'adv_board' => ['nullable', 'numeric']
        ];

        if(!request()->user()->hasAnyRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_ADVISORY_BOARDS])) {
            $rules['adv_board'] = ['required', 'numeric'];
        }
        foreach (config('available_languages') as $lang) {
            foreach (Publication::translationFieldsProperties() as $field => $properties) {
                $rules[$field.'_'.$lang['code']] = $properties['rules'];
            }
        }

        if(request()->isMethod('put')) {
            $rules['id'] = ['required', 'numeric', 'exists:publication,id'];
            $rules['file'] = ['nullable', 'file',  'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_IMAGES_EXTENSIONS)];

            foreach (config('available_languages') as $lang) {
                $rules['file_' . $lang['code']] = ['nullable', 'file',  'max:'.File::MAX_UPLOAD_FILE_SIZE, 'mimes:'.implode(',', File::ALLOWED_FILE_EXTENSIONS)];
                $rules['description_' . $lang['code']] = ['nullable', 'string'];
            }
        } else {
            $rules['file'] = ['required', 'file',  'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_IMAGES_EXTENSIONS)];
        }

        return $rules;
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'file.required' => 'Трябва да изберете Основна снимка',
        ];
    }
}
