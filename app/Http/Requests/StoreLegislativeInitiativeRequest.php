<?php

namespace App\Http\Requests;

use App\Models\LegislativeInitiative;
use App\Traits\FailedAuthorization;
use HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property LegislativeInitiative $item
 */
class StoreLegislativeInitiativeRequest extends FormRequest
{

    use FailedAuthorization;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'law_id' => ['required', 'numeric'],
            'institutions' => ['array', 'min:1'],
            'institutions.*' => ['numeric'],
            'description' => ['required', 'string'],
            'motivation' => ['required', 'string'],
            'law_paragraph' => ['required', 'string'],
            'law_text' => ['required', 'string'],
        ];
    }
}
