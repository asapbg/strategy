<?php

namespace App\Http\Controllers;

use App\Models\FormInput;

class ProfileController extends Controller
{
    public function index($tab = null)
    {
        $profile = app('auth')->user();
        $tab = $tab ? $tab : 'general_info';
        $formInputs = FormInput::whereUserId($profile->id)->get();
        return view('site.profile', compact('profile', 'tab', 'formInputs'));
    }
}