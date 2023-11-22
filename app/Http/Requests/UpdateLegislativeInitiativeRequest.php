<?php

namespace App\Http\Requests;

use App\Models\LegislativeInitiative;
use HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property LegislativeInitiative $item
 */
class UpdateLegislativeInitiativeRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (auth()->user()->cannot('update', $this->item)) {
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
        return [
            'cap' => ['required', 'numeric'],
        ];
    }
}
