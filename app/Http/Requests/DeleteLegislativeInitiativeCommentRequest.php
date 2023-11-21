<?php

namespace App\Http\Requests;

use App\Models\LegislativeInitiativeComment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DeleteLegislativeInitiativeCommentRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $comment = LegislativeInitiativeComment::find($this->route('comment')->id);

        return $comment && $this->user()->id === $comment->user_id;
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
