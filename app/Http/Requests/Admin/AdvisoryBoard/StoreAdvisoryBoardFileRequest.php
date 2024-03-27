<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Enums\DocTypesEnum;
use App\Models\AdvisoryBoard;
use App\Models\File;
use App\Rules\FileClientMimeType;
use App\Traits\FailedAuthorization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property int $item
 */
class   StoreAdvisoryBoardFileRequest extends FormRequest
{

    use FailedAuthorization;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', [AdvisoryBoard::class, $this->item]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'doc_type_id'                   => ['required', 'integer', Rule::in(DocTypesEnum::values())],
            'object_id'                     => ['required', 'integer'],
            'code_object'                => ['nullable', 'integer'],
            'resolution_council_ministers'  => ['nullable', 'string'],
            'state_newspaper'               => ['nullable', 'string'],
            'effective_at'                  => ['nullable', 'date'],
        ];


        $defaultLang = config('app.default_lang');
        foreach (config('available_languages') as $lang) {
//            $rules['file_' . $lang['code']] = ($defaultLang == $lang['code'] ? 'required|' : 'nullable|' ) .'file|mimes:pdf,doc,docx,xlsx';
//            $rules['file_name_' . $lang['code']] = ($defaultLang == $lang['code'] ? 'required|' : 'nullable|' ) .'string';
//            $rules['file_description_' . $lang['code']] = 'nullable|string';

            $rules['file_' . $lang['code']] = [($defaultLang == $lang['code'] ? 'required' : 'nullable' ),  new FileClientMimeType(File::ALL_ALLOWED_FILE_EXTENSIONS_MIMES_TYPE)];
            $rules['file_name_' . $lang['code']] = 'required|string';
            $rules['file_description_' . $lang['code']] = 'nullable|string';
        }

        return $rules;
    }
}
