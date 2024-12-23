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
        session(['hasErrorsFromFileTab' => false]);
        $rules = [
            'description_bg' => ['required', 'max:500'],
            'description_en' => ['nullable', 'max:500'],
            'valid_at' => ['required_if:date_valid_indefinite_files,0', 'date', 'nullable'],
            'strategic_document_type_id' => ['nullable', 'numeric', 'exists:strategic_document_type,id'],
            'parent_id' => ['sometimes', 'nullable','numeric', 'exists:strategic_document_file,id'],
            'is_visible_in_report' => ['nullable', 'numeric'],
            //'file' => ['required', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_FILE_EXTENSIONS)],
            //'ord' => ['required', 'numeric']
        ];

        foreach (config('available_languages') as $lang) {
            $rules['file_' .$lang['code']] = StrategicDocumentFileEnum::validationRules($lang['code']);//['required', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_FILE_EXTENSIONS)];
        }

//        foreach (config('available_languages') as $lang) {
//            foreach (StrategicDocumentFile::translationFieldsProperties() as $field => $properties) {
//                $rules[$field .'_'. $lang['code']] = $properties['rules'];
//            }
//        }

//        foreach (StrategicDocumentFile::translationFieldsProperties() as $field => $properties) {
//            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
//        }

        return $rules;
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'valid_at.required_if' => 'Полето ":attribute" е задължително, когато датата на изтичане не е неограничена.',
        ];
    }
}
