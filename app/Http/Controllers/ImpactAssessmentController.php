<?php

namespace App\Http\Controllers;

use App\Models\FormInput;
use PDF;

class ImpactAssessmentController extends Controller
{
    public function index()
    {
    }
    
    public function form($formName)
    {
        $state = $this->getState($formName);
        $step = app('request')->input('step', 1);
        $steps = $this->getSteps($formName);
        $inputId = app('request')->input('inputId', 0);
        return view('site.impact_assessment', compact('formName', 'state', 'step', 'steps', 'inputId'));
    }

    public function store($formName)
    {
        $userId = app('auth')->id();
        $state = $this->getState($formName);
        $data = app('request')->except('_token');
        if (array_key_exists('add_entry', $data)) {
            $data[substr($data['add_entry'], 0, -2)][] = '';
            unset($data['add_entry']);
        }
        if (array_key_exists('add_array_entry', $data)) {
            $key = $data['add_array_entry'];
            $value = data_get($data, $key, []);
            array_push($value, []);
            data_set($data, $key, $value);
            unset($data['add_array_entry']);
        }
        
        $data = array_merge($state, $data);
        session(["forms.$formName" => $data]);
        
        $inputId = app('request')->input('inputId', 0);

        if ($userId) {
            $fi = $inputId ? FormInput::find($inputId) : FormInput::firstOrNew([
                'form' => $formName,
                'user_id' => $userId
            ]);
            $fi->data = json_encode($data);
            $fi->save();
            $inputId = $fi->id;
        }
        
        $step = app('request')->input('step');
        if (app('request')->input('submit')) {
            return view('impact_assessment.submitted', compact('formName', 'inputId'));
        }
        return redirect()->route('impact_assessment.form', ['form' => $formName, 'step' => $step, 'inputId' => $inputId]);
    }

    public function pdf($formName)
    {
        $state = $this->getState($formName);
        $steps = $this->getSteps($formName);
        $readOnly = true;
        $pdf = PDF::loadView('impact_assessment.pdf', compact('formName', 'steps', 'state', 'readOnly'));
        return $pdf->download("$formName.pdf");
    }
    
    private function getState($formName) {
        $state = session("forms.$formName", []);
        if ($inputId = app('request')->input('inputId')) {
            $item = FormInput::find($inputId);
            $state = json_decode($item->data, true);
        }
        return $state;
    }

    private function getSteps($formName) {
        return count(\File::allFiles(resource_path("views/form_partials/$formName/steps")));
    }
}
