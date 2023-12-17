<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OgpAreaOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $offer = $this->request->get('offer', 0);

        if($offer) {
            return [
                'commitment_name' => 'required_without:commitment_id|max:255',
                'commitment_id' => 'required_without:commitment_name',
                'arrangement_name' => 'required|max:255',
                'fields.*' => 'required'
            ];
        }

        return [
            'commitment_name' => 'required|max:255',
            'arrangement_name' => 'required',
            'fields.*' => 'required'
        ];
    }

    public function messages(): array
    {
        $messages = parent::messages();
        $messages['commitment_id.gt'] = __('validation.required');
        $messages['arrangement_id.gt'] = __('validation.required');
        return $messages;
    }


}
