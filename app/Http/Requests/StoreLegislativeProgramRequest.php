<?php

namespace App\Http\Requests;

use App\Models\Consultations\LegislativeProgram;
use App\Models\File;
use App\Rules\DateCrossProgram;
use App\Rules\ProgramValidPeriod;
use Illuminate\Foundation\Http\FormRequest;

class StoreLegislativeProgramRequest extends FormRequest
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
        $defaultLang = config('app.default_lang');
        $rules = [
            'id' => ['required', 'numeric'],
            'new_row' => ['nullable', 'numeric'],
            'save' => ['nullable', 'numeric'],
            'save_files' => ['nullable', 'numeric'],
            'stay_in_files' => ['nullable', 'numeric'],
        ];

        if( request()->input('save') ) {
            $rules['from_date'] = ['required', 'string', 'date_format:m.Y', new DateCrossProgram(request()->input('to_date'), true, 'legislative', request()->input('id'))]; //, new ProgramValidPeriod(request()->input('to_date'))
            $rules['to_date'] = ['required', 'string', 'date_format:m.Y', new DateCrossProgram(request()->input('from_date'), false, 'legislative', request()->input('id'))];
            if( request()->input('id') ) {
                $rules['col'] = ['array'];
                $rules['col.*'] = ['array'];
                $rules['col.*.*'] = ['required', 'numeric', 'exists:legislative_program_row,id'];
                $rules['val'] = ['array'];
                $rules['val.*'] = ['array'];

                if(request()->filled('col')) {
                    foreach (request()->input('col') as $key => $columns) {
                        foreach ($columns as $key2 => $ids) {
                            $rules['val.' . $key . '.' . $key2] = ['nullable'];
                        }
                    }
                }
            }

//            foreach (request()->all() as $key => $field) {
//                //detect all row files //input name format {fiel_type}_{row_num}_{row_month}
//                //row_num and row_month are pivot columns in program row files relationship
//                if( str_contains($key,'file_assessment') || str_contains($key, 'file_opinion') ) {
//                    $rules[$key] = [ 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', ['pdf'])];
//                }
//            }
        }

        if( request()->input('new_row') ) {
            $rules['new_val_col'] = ['array'];
            $rules['new_val_col.*'] = ['required', 'numeric', 'exists:dynamic_structure_column,id'];
            $rules['new_val'] = ['array'];
            $rules['month'] = ['required_with:new_val', 'string', 'max:7'];
            foreach (request()->input('new_val_col') as $key => $input) {
                if($input == config('lp_op_programs.lp_ds_col_institution_id')) {
                    $rules['new_val.'.$key] = ['required', 'array'];
                }elseif($input == config('lp_op_programs.lp_ds_col_title_id')) {
                    $rules['new_val.'.$key] = ['required', 'string'];
                } else{
                    $rules['new_val.'.$key] = ['nullable', 'string'];
                }
            }
        }

        if( request()->input('save_files') || request()->input('stay_in_files')) {
            foreach (config('available_languages') as $lang) {
                $rules['file_' . $lang['code']] = ['nullable', 'file',  'max:'.File::MAX_UPLOAD_FILE_SIZE, 'mimes:'.implode(',', File::ALLOWED_FILE_EXTENSIONS)];
                $rules['description_' . $lang['code']] = [($defaultLang == $lang['code'] ? 'required' : 'nullable'), 'string'];
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'month.required_with' => 'Задължително поле'
        ];

        foreach (request()->all() as $key => $field) {
            if( str_contains($key,'file_assessment') ) {
                $messages[$key.'.'.'file'] = trans('validation.file', ['attribute' => trans('validation.attributes.assessment')]);
                $messages[$key.'.'.'max'] = trans('validation.max.file', ['attribute' => trans('validation.attributes.assessment'), 'max' => config('filesystems.max_upload_file_size')]);
                $messages[$key.'.'.'mimes'] = trans('validation.mimes', ['attribute' => trans('validation.attributes.assessment'), 'values' => implode(',', ['pdf'])]);
            }
            if( str_contains($key, 'file_opinion') ) {
                $messages[$key.'.'.'file'] = trans('validation.file', ['attribute' => trans('validation.attributes.opinion')]);
                $messages[$key.'.'.'max'] = trans('validation.max.file', ['attribute' => trans('validation.attributes.opinion'), 'max' => config('filesystems.max_upload_file_size')]);
                $messages[$key.'.'.'mimes'] = trans('validation.mimes', ['attribute' => trans('validation.attributes.opinion'), 'values' => implode(',', ['pdf'])]);
            }
        }

        return $messages;
    }
}
