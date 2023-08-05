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
        return view('site.impact_assessment', compact('formName', 'state'));
    }

    public function store($formName)
    {
        $data = app('request')->except('_token');
        if (array_key_exists('add_entry', $data)) {
            $data[substr($data['add_entry'], 0, -2)][] = '';
        }
        if (array_key_exists('add_array_entry', $data)) {
            $key = $data['add_array_entry'];
            $value = data_get($data, $key, []);
            array_push($value, []);
            data_set($data, $key, $value);
        }
        session(["forms.$formName" => $data]);
        return redirect()->back();
    }
}
