<?php

namespace App\Services;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\AuthTrait;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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


        $credentials = request(['email' , 'password']);
        if(!Auth::attempt($credentials, $request->remember)){
            $this->limiter->hit('loginAttempt');
            $this->failedAttempt($request->email);

            $userStatus = $this->checkUserStatus($request->email);
            if ($userStatus["banned"]){
                return array(
                    "success" => false,
                    "message" => "Sorry your account is temporarily blocked. \n You may try again at ". $userStatus["until"] ." (current time ". Carbon::now()->format('Y-m-d h:i A') .") \n or call your system admin for assistance.",
                    "code" => 409
                );
            }

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


    public function forgotPassword($request)
    {

        $token = Str::random(30);
        $user = $this->getBy("email", $request->email);
        DB::table("password_resets")->where("email", $request->email)->delete();
        DB::beginTransaction();

        DB::table("password_resets")->upsert([
            ["email" => $request->email, "token" => $token, "valid_until" => Carbon::now()->addMinutes(60)]
        ], ["email" => $request->email]);

        $data = array(
            "token"     => $token,
            "url"       => $request->url . "?token=".$token."&email=".$user->email,
            "full_name" => $user->full_name,
            "email"     => $user->email
        );
        Mail::to($user)->send(new ResetPasswordMail($data));
        DB::commit();

    }


    public function resetPassword($request)
    {
        $status = false;
        $msg =  "Invalid Token - Please try to request new reset link ";
        $pr = DB::table("password_resets")->where("email", $request->email)->where("token", $request->token)->first();
        if ((bool)$pr) {
            $now = Carbon::now();
            $valid = $now->lessThan($pr->valid_until);
            if($valid) {
                $user = $this->model->where("email", $pr->email)->first();
                $user->update(["password" => $request->password, "banned_until" => null, "attempt" => 0]);
                DB::table("password_resets")->where("email", $pr->email)->delete();
                $status = true;
                $msg =  "Password successfully updated - you can try to login now." ;
            } else {
                $msg =  "Token Expired - please try to request new reset link ";
            }
        }

        return array(
            "message" => $msg,
            "success" => $status
        );
    }

}
