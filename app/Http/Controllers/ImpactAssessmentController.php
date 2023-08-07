<?php

namespace App\Http\Controllers;

class ImpactAssessmentController extends Controller
{
    public function index()
    {
    }
    
    public function form($formName)
    {
        $state = session("forms.$formName", []);
        $step = app('request')->input('step', 1);
        $steps = count(\File::allFiles(resource_path("views/form_partials/$formName/steps")));
        return view('site.impact_assessment', compact('formName', 'state', 'step', 'steps'));
    }

    public function store($formName)
    {
        $state = session("forms.$formName", []);
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
        $step = app('request')->input('step');
        if (app('request')->input('submit')) {
            return view('impact_assessment.submitted', compact('formName'));
        }
        return redirect()->route('impact_assessment.form', ['form' => $formName, 'step' => $step]);
    }

    public function pdf($formName)
    {
        $state = session("forms.$formName", []);
        $steps = count(\File::allFiles(resource_path("views/form_partials/$formName/steps")));
        return view('impact_assessment.pdf', compact('formName', 'steps', 'state'));
    }
}
