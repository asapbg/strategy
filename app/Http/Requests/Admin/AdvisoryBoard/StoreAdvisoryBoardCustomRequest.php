<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardCustom;
use App\Models\AdvisoryBoardSecretariat;
use App\Traits\TranslatableFieldsRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property AdvisoryBoard $item
 */
class StoreAdvisoryBoardCustomRequest extends FormRequest
{
    use TranslatableFieldsRules;

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
//        $rules = [
//            'title' => 'nullable|string',
//        ];

//        return $rules;

        $rules = $this->getRules([], AdvisoryBoardCustom::translationFieldsProperties());

        $defaultLang = config('app.default_lang');
        foreach (config('available_languages') as $lang) {
//                $rules['file_' . $lang['code']] = ($defaultLang == $lang['code'] ? 'required|' : 'nullable|' ). 'array';
                $rules['file_' . $lang['code']] = 'nullable|array';
                $rules['file_' . $lang['code'] . '.*'] = ($defaultLang == $lang['code'] ? 'required|' : 'nullable|' ) .'file|mimes:pdf,doc,docx,xlsx|max:2048'.config('filesystems.max_upload_file_size');
                $rules['file_name_' . $lang['code']] = 'nullable|array';
                $rules['file_description_' . $lang['code']] = 'nullable|array';
        }

        return $rules;
    }
}
