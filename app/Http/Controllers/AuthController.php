<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Input;
use Illuminate\Support\Facades\Hash;
// use JWTAuth;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

	
    /**
     * @OA\Post(
     *   path="/pointsystem/public/v1/login",
     *   tags={"Login"},
     *   summary="Login to authnticate user",
     *   description="Authenticate User",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      description="User email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      description="User password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Incomplete parameter(s)",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
     **/
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['response_code' => '02', 'message' => $validator->errors()], 422);
        }

        if (! $token = app('auth')->attempt($validator->validated())) {
            return response()->json(['response_code' => '01', 'message' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    protected function createNewToken($token){
        return response()->json([
            'response_code' => '00',
            'message' =>'success',
            'data'=>[
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ]);
    }

    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function decode(Request $request)
    {
        $token = JWTAuth::getToken();

        $user = JWTAuth::toUser($token);

        return $user;
    }

	
}