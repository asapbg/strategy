<?php

namespace App\Http\Requests\Admin\AdvisoryBoard;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardFunction;
use App\Traits\FailedAuthorization;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int $item
 */
class StoreAdvisoryBoardFunctionRequest extends FormRequest
{

    use FailedAuthorization;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', AdvisoryBoard::find($this->item));
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
            foreach (AdvisoryBoardFunction::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
