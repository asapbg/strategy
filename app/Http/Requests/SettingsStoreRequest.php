<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;

class SettingsStoreRequest extends FormRequest
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
        $rules = [];
        $section = request()->input('section');
        $dbSettings = Setting::where('section', '=', $section)->get()->toArray();

        if( sizeof($dbSettings) ) {
            $rules['section'] = ['required', 'string'];
            foreach ($dbSettings as $s) {
                $rules[$s['name']] = [
                    $s['is_required'] ? 'required' : 'nullable',
                    $s['type'] == 'numeric' ? 'numeric' : 'string'
                ];

                if($s['name'] == Setting::OGP_LEGISLATIVE_INIT_REQUIRED_LIKES){
                    $rules[$s['name']][] = 'min:1';
                }
            }
        }

        return $rules;
    }
}
