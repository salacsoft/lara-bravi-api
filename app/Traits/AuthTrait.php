<?php

namespace App\Traits;

use config;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait AuthTrait {

   public function failedAttempt($email)
   {
      $user  = $this->user->where("email", $email)->first();
      if($user){
         $user->update(["attempt" => $user->attempt += 1]);
         Log::info("atttempt " . $user->attemtpt);
         if (config('bravi.max_login_attempt') <=  $user->attempt ) {
            $this->banUser($email);
         }

      }


   }

   public function banUser($email)
   {
      $time = Carbon::now()->addMinutes(config('bravi.auth_banned_minutes'));
      $user  = $this->user->where("email", $email)->first();
      if($user){
         $user->update(["banned_until" => $time, "attempt" => 0]);
      }
   }

   public function unBannedUser($email)
   {
      $user  = $this->user->where("email", $email)->first();
      if($user){
         $user->update(["banned_until" => null, "attempt" => 0, "banned_until" => null]);
      }
   }

   public function checkUserStatus($email)
   {
      $user  = $this->user->where("email", $email)->first();
      if($user && $user->banned_until){
         $now = Carbon::now();
         $banned = $now->lt($user->banned_until);
         if ($banned) {
            return array(
               "banned" => true,
               "until" => Carbon::parse($user->banned_until)->format("Y-m-d h:i A")
            );
         }
      }
      return array(
         "banned" => false,
         "until" => null
      );
   }
}
