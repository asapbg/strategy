<?php

namespace App\Http\Requests\Admin\LegislativeInitiative;

use App\Models\LegislativeInitiativeComment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @property LegislativeInitiativeComment $comment
 */
class AdminDeleteLegislativeInitiativeCommentRequest extends FormRequest
{

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
        return [];
    }
}
