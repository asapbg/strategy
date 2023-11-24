<?php

namespace App\Http\Requests\Admin\LegislativeInitiative;

use App\Models\LegislativeInitiativeComment;
use App\Traits\FailedAuthorization;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property LegislativeInitiativeComment $comment
 */
class AdminDeleteLegislativeInitiativeCommentRequest extends FormRequest
{

    use FailedAuthorization;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('delete', $this->comment);
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
