<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardCustom;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property AdvisoryBoard $item
 */
class StoreAdvisoryBoardCustomRequest extends FormRequest
{

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
            'title' => 'nullable|string',
            'order' => 'nullable|integer',
        ];

        foreach (config('available_languages') as $lang) {
            foreach (AdvisoryBoardCustom::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
                $rules['file_' . $lang['code']] = 'nullable|array';
                $rules['file_' . $lang['code'] . '.*'] = 'required|file|mimes:pdf,doc,docx,xlsx|max:2048';
            }
        }

        return $rules;
    }
}
