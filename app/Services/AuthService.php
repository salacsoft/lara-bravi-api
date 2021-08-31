<?php

namespace App\Services;

use App\Models\User;
use App\Traits\AuthTrait;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Auth;

class AuthService  extends BaseService
{
    use AuthTrait;

    protected $user;
    protected $limiter;

    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->user = $this->model;
        $this->limiter = app(RateLimiter::class);
    }


    public function authenticate($request)
    {
        if ($this->limiter->tooManyAttempts('loginAttempt', 50)) {
            return array(
                "success" => false,
                "message" => "Too many attempts - please wait for a while before sending again",
                "code" => 429
            );
        }

        $userStatus = $this->checkUserStatus($request->email);
        if ($userStatus["banned"]){
            return array(
                "success" => false,
                "message" => "Sorry your account is temporarily blocked.<br> You may try again at ". $userStatus["until"]  ." <br> or call your system admin for assistance.",
                "code" => 409
            );
        }

        $credentials = request(['email' , 'password']);
        if(!Auth::attempt($credentials, $request->remember)){
            $this->limiter->hit('loginAttempt');
            $this->failedAttempt($request->email);
            return array(
                "success" => false,
                "message" => "Incorrect Email or Password, Please check your credential and try again",
                "code" => 401
            );
        }
        $this->limiter->clear('loginAttempt');
        $this->unBannedUser($request->email);
        return array(
            "success" => true,
            "message" => "User logged in",
            "code" => 200
        );
    }

}
