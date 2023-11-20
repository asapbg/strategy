<?php

namespace App\Http\Requests;

use App\Models\LegislativeInitiative;
use HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property LegislativeInitiative $item
 */
class StoreLegislativeInitiativeRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (
            auth()->user()->cannot('update', $this->item) &&
            auth()->user()->cannot('create', LegislativeInitiative::class)
        ) {
            return false;
        }

        return true;
    }

    /**
     * How to handle failed authorization.
     *
     * @return HttpResponseException
     */
    public function failedAuthorization(): HttpResponseException
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            redirect()->back()->with('warning', __('messages.unauthorized'))
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'regulatory_act_id' => ['required', 'numeric'],
            'description_' . app()->getLocale() => ['required', 'string'],
            'author_' . app()->getLocale() => ['required']
        ];

        foreach (config('available_languages') as $lang) {
            foreach (LegislativeInitiative::translationFieldsProperties() as $field => $properties) {
                $rules[$field . '_' . $lang['code']] = $properties['rules'];
            }
        }

        return $rules;
    }
}
