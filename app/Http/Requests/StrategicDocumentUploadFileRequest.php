<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;

class StrategicDocumentUploadFileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'description_bg' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string', 'max:255'],
            'is_visible_in_report' => ['nullable', 'numeric'],
            'formats' => ['required', 'string'],
            'file_bg' => ['required', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_FILE_STRATEGIC_DOC)],
            'file_en' => ['nullable', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_FILE_STRATEGIC_DOC)]
        ];
    }
}
