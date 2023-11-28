<?php

namespace App\Http\Requests;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunctionFile;
use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvisoryBoardFunctionFileRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', [AdvisoryBoardFunctionFile::class, File::find($this->request->get('file_id', 0))]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'file' => 'required|file|mimes:pdf,doc,docx,xlsx',
            'file_id' => 'required|integer|exists:files,id'
        ];

        foreach ($this->request->all() as $name => $value) {
            if (str_contains($name, 'file_description')) {
                $rules[$name] = 'nullable|string';
            }

            if (str_contains($name, 'file_name')) {
                $rules[$name] = 'required|string';
            }
        }

        return $rules;
    }
}
