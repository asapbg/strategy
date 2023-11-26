<?php

namespace App\Http\Requests;

use App\Models\LegislativeInitiativeComment;
use App\Traits\FailedAuthorization;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property LegislativeInitiativeComment $comment
 */
class DeleteLegislativeInitiativeCommentRequest extends FormRequest
{

    use FailedAuthorization;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->comment && $this->user()->id === $this->comment->user_id;
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
