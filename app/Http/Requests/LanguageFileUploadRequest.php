<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageFileUploadRequest extends FormRequest
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
        if ($this->routeIs('publications.store')) {
            return [];
        }
        $formatInput = request()->input('formats');
        $formats = constant("\App\Models\File::$formatInput");
        return [
            'description_bg' => ['nullable', 'string', 'max:255'],
            'description_en' => ['nullable', 'string', 'max:255'],
            'formats' => ['required', 'string'],
            'file_bg' => ['required', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', $formats)],
            'file_en' => ['nullable', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', $formats)]
        ];
    }
}
