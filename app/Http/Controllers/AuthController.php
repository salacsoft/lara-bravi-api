<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Traits\ResponseTrait;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LoginResource;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;

class AuthController extends Controller
{
    use ResponseTrait;

    protected $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    public function login(LoginRequest $request)
    {
        $auth = $this->auth->authenticate($request);

        if(!$auth["success"]) {
            return $this->failure($auth["message"], $auth["code"]);
        }
        //delete all previous token
        $request->user()->tokens()->delete();
        //get user information
        $user = $request->user();
        return $this->success(
            "User Logged in",
            new LoginResource($user),
            200
        );
    }


    public function forgotPassword(ForgotPasswordRequest $request)
    {
        if ($this->auth->forgotPassword($request)) {
            return response()->json([
                "message" => "We sent reset link to your email"
            ], 200);
        }
    }


    public function resetPassword(ResetPasswordRequest $request)
    {
        $response = $this->auth->resetPassword($request);
        Log::info($response);
        if (!$response["success"]) {
            return response()->json([
                "message" => $response["message"]
            ], 400);
        }

        return response()->json([
            "message" => "Password Succesfully changed, you can login now"
        ], 200);

    }

    public function logOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}
