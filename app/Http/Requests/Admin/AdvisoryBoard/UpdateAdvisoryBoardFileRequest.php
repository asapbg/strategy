<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Enums\DocTypesEnum;
use App\Models\AdvisoryBoard;
use App\Traits\FailedAuthorization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property AdvisoryBoard $item
 */
class UpdateAdvisoryBoardFileRequest extends FormRequest
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
            'file_id'                       => ['required', 'integer', 'exists:files,id'],
            'file'                          => ['nullable', 'file', 'mimes:pdf,doc,docx,xlsx'],
            'resolution_council_ministers'  => ['nullable', 'string'],
            'state_newspaper'               => ['nullable', 'string'],
            'effective_at'                  => ['nullable', 'date'],
        ];

        foreach (config('available_languages') as $lang) {
            if ($this->request->has('file_name_' . $lang['code'])) {
                $rules['file_name_' . $lang['code']] = 'required|string';
                $rules['file_description_' . $lang['code']] = 'nullable|string';
            }
        }

        return $rules;
    }
}
