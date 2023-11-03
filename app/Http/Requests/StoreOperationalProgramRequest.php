<?php

namespace App\Http\Requests;

use App\Models\Consultations\OperationalProgram;
use App\Rules\DateCrossProgram;
use Illuminate\Foundation\Http\FormRequest;

class StoreOperationalProgramRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            'id' => ['required', 'numeric'],
            'new_row' => ['nullable', 'numeric'],
            'save' => ['nullable', 'numeric'],
        ];

        if( request()->input('save') ) {
            $rules['from_date'] = ['required', 'string', 'date_format:m.Y', new DateCrossProgram(true, 'operational', request()->input('id'))];
            $rules['to_date'] = ['required', 'string', 'date_format:m.Y', new DateCrossProgram(false, 'operational', request()->input('id'))];
//            $rules['assessment'] = ['nullable', 'file', 'max:' . config('filesystems.max_upload_file_size'), 'mimes:' . implode(',', ['pdf'])];
//            $rules['opinion'] = ['nullable', 'file', 'max:' . config('filesystems.max_upload_file_size'), 'mimes:' . implode(',', ['pdf'])];
//            $rules['assessment'] = ['array'];
//            $rules['assessment.*'] = ['file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', ['pdf'])];
//            $rules['opinion'] = ['array'];
//            $rules['opinion.*'] = [ 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', ['pdf'])];

            if (request()->input('id')) {
                $rules['col'] = ['array'];
                $rules['col.*'] = ['required', 'numeric', 'exists:operational_program_row,id'];
                $rules['val'] = ['array'];
                $rules['val.*'] = ['required', 'string', 'max:255'];
            }
        }

        if( request()->input('new_row') ) {
            $rules['new_val_col'] = ['array'];
            $rules['new_val_col.*'] = ['required', 'numeric', 'exists:dynamic_structure_column,id'];
            $rules['new_val'] = ['array'];
            $rules['new_val.*'] = ['required', 'string', 'max:255'];
            $rules['month'] = ['required_with:new_val', 'string', 'max:7'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'month.required_with' => 'Задължително поле'
        ];
    }
}
