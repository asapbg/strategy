<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdvBoardMembersOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => ['required', 'numeric', 'exists:advisory_boards'],
            'member' => ['required', 'array'],
            'member.*' => ['required', 'numeric'],
            'member_ord' => ['required', 'array'],
            'member_ord.*' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
