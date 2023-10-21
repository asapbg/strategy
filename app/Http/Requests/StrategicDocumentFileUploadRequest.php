<?php

namespace App\Http\Requests;

use App\Models\File;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentFile;
use Illuminate\Foundation\Http\FormRequest;

class StrategicDocumentFileUploadRequest extends FormRequest
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
            'id' => ['required', 'numeric', 'exists:strategic_document,id'],
            'valid_at' => ['required', 'date'],
            'strategic_document_type' => ['required', 'numeric', 'exists:strategic_document_type,id'],
            'visible_in_report' => ['nullable', 'numeric'],
            'file' => ['required', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_FILE_EXTENSIONS)],
            'ord' => ['required', 'numeric']
        ];

        foreach (StrategicDocumentFile::translationFieldsProperties() as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }
        return $rules;
    }
}
