<?php

namespace App\Http\Requests;

use App\Models\LegislativeInitiative;
use App\Traits\FailedAuthorization;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property LegislativeInitiative $item
 */
class UpdateLegislativeInitiativeRequest extends FormRequest
{

    use FailedAuthorization;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check() && ($this->item->author_id === auth()->user()->id);
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
