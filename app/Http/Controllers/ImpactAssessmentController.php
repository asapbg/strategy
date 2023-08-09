<?php

namespace App\Http\Controllers;

use App\Models\FormInput;
use App\Models\RegulatoryAct;
use App\Models\StrategicDocuments\Institution;
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
            $value = data_get($data, $key, [[]]);
            array_push($value, []);
            data_set($data, $key, $value);
            unset($data['add_array_entry']);
        }
        
        $data = array_merge($state, $data);
        session(["forms.$formName" => $data]);
        
        $inputId = app('request')->input('inputId', 0);
        $submit = app('request')->input('submit');

        if ($userId || $submit) {
            $fi = FormInput::find($inputId);
            if (!$fi) {
                $fi = new FormInput([
                    'form' => $formName,
                    'user_id' => $userId,
                ]);
            }
            $fi->data = json_encode($data);
            $fi->save();
            $inputId = $fi->id;
        }
        
        $step = app('request')->input('step');
        if ($submit) {
            session(["forms.$formName" => []]);
            return view('impact_assessment.submitted', compact('formName', 'inputId'));
        }
        return redirect()->route('impact_assessment.form', ['form' => $formName, 'step' => $step, 'inputId' => $inputId]);
    }

    public function show($formName, $inputId)
    {
        $state = $this->getState($formName, $inputId);
        $steps = $this->getSteps($formName);
        $readOnly = true;
        return view('impact_assessment.show', compact('formName', 'steps', 'state', 'readOnly'));
    }

    public function pdf($formName, $inputId)
    {
        $state = $this->getState($formName, $inputId);
        $steps = $this->getSteps($formName);
        $readOnly = true;
        $pdf = PDF::loadView('impact_assessment.pdf', compact('formName', 'steps', 'state', 'readOnly'));
        return $pdf->download("$formName.pdf");
    }
    
    private function getState($formName, $inputId = null) {
        $state = session("forms.$formName", []);
        if (!$inputId) $inputId = app('request')->input('inputId');
        if ($inputId) {
            $item = FormInput::find($inputId);
            $state = json_decode($item->data, true);
        }
        return $state;
    }

    private function getSteps($formName) {
        return count(\File::allFiles(resource_path("views/form_partials/$formName/steps")));
    }
}
