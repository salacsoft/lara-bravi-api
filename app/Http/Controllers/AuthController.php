<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Traits\ResponseTrait;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;

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
}
