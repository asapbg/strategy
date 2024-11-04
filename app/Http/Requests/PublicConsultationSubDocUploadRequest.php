<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicConsultationSubDocUploadRequest extends FormRequest
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
            'stay' => ['nullable', 'numeric'],
            'id' => ['required', 'numeric'],
            'parent_type' => ['required', 'numeric'],
            'description_bg' => ['nullable', 'string', 'max:255', 'required_without:description_en', 'required_with:file_bg'],
            'description_en' => ['nullable', 'string', 'max:255', 'required_without:description_bg', 'required_with:file_en'],
            'file_bg' => ['nullable', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'required_with:description_bg'],
            'file_en' => ['nullable', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'required_with:description_en']
            ];
    }
}
