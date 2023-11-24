<?php

namespace App\Http\Requests;

use App\Models\LegislativeInitiative;
use App\Traits\FailedAuthorization;
use HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property LegislativeInitiative $item
 */
class RestoreLegislativeInitiativeRequest extends FormRequest
{

    use FailedAuthorization;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->can('restore', LegislativeInitiative::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric'],
        ];
    }
}
