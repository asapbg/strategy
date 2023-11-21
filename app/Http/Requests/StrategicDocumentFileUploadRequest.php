<?php

namespace App\Http\Requests;

use App\Enums\StrategicDocumentFileEnum;
use App\Models\File;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

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
            'strategic_document_type_id' => ['required', 'numeric', 'exists:strategic_document_type,id'],
            'parent_id' => ['sometimes', 'nullable','numeric', 'exists:strategic_document_file,id'],
            'visible_in_report' => ['nullable', 'numeric'],
            //'file' => ['required', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_FILE_EXTENSIONS)],
            //'ord' => ['required', 'numeric']
        ];

        foreach (config('available_languages') as $lang) {
            $rules['file_strategic_documents_' .$lang['code']] = StrategicDocumentFileEnum::validationRules($lang['code']);//['required', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_FILE_EXTENSIONS)];
        }

        foreach (config('available_languages') as $lang) {
            foreach (StrategicDocumentFile::translationFieldsProperties() as $field => $properties) {
                $rules[$field .'_'. $lang['code']] = $properties['rules'];
            }
        }
        /*
        foreach (StrategicDocumentFile::translationFieldsProperties() as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }
        */
        //dd($rules);
        return $rules;
    }
}
