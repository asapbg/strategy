<?php

namespace App\Http\Requests;

use App\Models\AdvisoryBoard;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdvisoryBoardFunctionFileRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', AdvisoryBoard::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,docx,xlsx',
            'file_name' => 'required|string|min:3',
            'file_description' => 'nullable|string',
        ];
    }
}
