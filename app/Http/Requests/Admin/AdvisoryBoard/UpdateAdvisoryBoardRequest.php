<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\File;
use App\Traits\FailedAuthorization;
use App\Traits\RequestCreateNecessaryNomenclaturesTrait;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $item
 */
class UpdateAdvisoryBoardRequest extends FormRequest
{

    use FailedAuthorization, RequestCreateNecessaryNomenclaturesTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->item);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $this->createNecessaryNomenclatures();

        $rules = [
            'policy_area_id'            => 'required|integer|exists:field_of_actions,id',
            'advisory_chairman_type_id' => 'required|integer|exists:advisory_chairman_type,id',
            'advisory_act_type_id'      => 'required|integer|exists:advisory_act_type,id',
            'authority_id'              => 'required|integer|exists:authority_advisory_board,id',
            'meetings_per_year'         => 'nullable|integer',
            'has_npo_presence'          => 'nullable',
            'integration_link'          => 'nullable|string',
            'public'                    => 'nullable|integer',
            'file'                      => ['nullable', 'file',  'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_IMAGES_EXTENSIONS)],
            'no_meetings_per_year'      => 'nullable',
        ];

        $rules['npo_id'] = ['nullable', 'array'];
        $defaultLang = config('app.default_lang');
        foreach (config('available_languages') as $lang) {
            $rules['npo_' . $lang['code']] = ['nullable'];
            foreach (AdvisoryBoard::translationFieldsProperties() as $field => $properties) {
                $fieldName = $field . '_' . $lang['code'];
                $mainLang = $lang['code'] == $defaultLang;
                $fieldRules = $properties['rules'];
                if(isset($properties['required_all_lang']) && !$properties['required_all_lang'] && !$mainLang) {
                    if (($key = array_search('required', $fieldRules)) !== false) {
                        if(empty(request()->input($fieldName))){
                            $fieldRules = [];
                        } else{
                            unset($fieldRules[$key]);
                        }
                    }
                }

                if(sizeof($fieldRules)) {
                    $rules[$fieldName] = $fieldRules;
                }
            }
        }
        return $rules;
    }
}
