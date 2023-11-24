<?php

namespace App\Http\Requests\Admin\LegislativeInitiative;

use App\Models\LegislativeInitiative;
use App\Traits\FailedAuthorization;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property LegislativeInitiative $item
 */
class AdminViewLegislativeInitiativeRequest extends FormRequest
{

    use FailedAuthorization;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('view', $this->item);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
