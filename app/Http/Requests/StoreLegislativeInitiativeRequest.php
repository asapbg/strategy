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
        return auth()->check();
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
        return [
            'operational_program_id' => ['required', 'numeric'],
            'description' => ['required', 'string'],
        ];
    }
}
