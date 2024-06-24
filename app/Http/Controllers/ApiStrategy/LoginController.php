<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends ApiController
{
    public function login(Request $request){
        $validator = \Illuminate\Support\Facades\Validator::make($this->request_inputs, [
            'email'=>'required|string|email',
            'password'=>'required|string|min:6'
        ]);

        if($validator->fails()){
            return $this->returnError(Response::HTTP_UNAUTHORIZED, $validator->errors()->first());
        }

        $validated = $validator->validated();

        //TODO add role to Seeders, hide role, create user for sanctum, only service-user can create this type user
        $user = User::where('email',$validated['email'])->first();

        if(!$user->hasRole('sanctum-user')){
            return $this->returnError(Response::HTTP_UNAUTHORIZED, 'Invalid Credentials');
        }

        if(!$user || !Hash::check($validated['password'],$user->password)){
            return $this->returnError(Response::HTTP_UNAUTHORIZED, 'Invalid Credentials');
        }
        $user->tokens()->delete();
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        return $this->output(['token' => $token]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return $this->output(['msg' => 'logged out']);
    }
}
