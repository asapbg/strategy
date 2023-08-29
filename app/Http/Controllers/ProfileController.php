<?php

namespace App\Http\Controllers;

use App\Models\FormInput;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index($tab = null)
    {
        $profile = app('auth')->user();
        $tab = $tab ? $tab : 'change_info';
        $formInputs = FormInput::whereUserId($profile->id)->get();
        return view('site.profile', compact('profile', 'tab', 'formInputs'));
    }

    public function store(Request $request) {
        $user = app('auth')->user();
        $data = $request->all();
        dd($data);
        if ($user->is_org) {
            $user->org_name = $request->input('org_name');
        } else {
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
        }
        $user->save();
        return redirect()->back();
    }
}