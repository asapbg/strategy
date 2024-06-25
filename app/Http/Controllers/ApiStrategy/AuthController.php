<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends ApiController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        parent::__construct($request);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $validator = \Illuminate\Support\Facades\Validator::make($this->request_inputs, [
            'email'=>'required|string|email',
            'password'=>'required|string|min:6'
        ]);

        if($validator->fails()){
            return $this->returnError(Response::HTTP_UNAUTHORIZED, $validator->errors()->first());
        }

        $credentials = $validator->validated();

        $user = User::where('email',$credentials['email'])->first();
        if(!$user->hasRole('sanctum-user')){
            return $this->returnError(Response::HTTP_UNAUTHORIZED, 'Invalid Credentials');
        }

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->returnError(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return $this->output(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
//            'expires_in' => null
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
