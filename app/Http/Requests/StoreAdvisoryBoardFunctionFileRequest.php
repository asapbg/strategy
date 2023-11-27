<?php

namespace App\Http\Requests;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunctionFile;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdvisoryBoardFunctionFileRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', AdvisoryBoard::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [];

        foreach (config('available_languages') as $lang) {
            $rules['file_' . $lang['code']] = 'required|file|mimes:pdf,doc,docx,xlsx';

            foreach (AdvisoryBoardFunctionFile::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
