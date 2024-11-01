<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function confirmPassword(Request $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if( $validator->fails() ) {
            return back()->with('danger', __('custom.check_for_errors'))->withInput()->withErrors($validator->errors()->all());
        }

        $validated = $validator->validated();
        $user = User::where('email', '=', strtolower($validated['email']))->first();

        if( $user ) {
            $user->password = Hash::make($validated['password']);
            $user->password_changed_at = Carbon::now();
            $user->save();

            return redirect('login')->with('success', __('Паролата е сменена успешно'));
        }

        return back()->with('danger', __('custom.system_error'))->withInput()->withErrors($validator->errors());
    }
}
