<?php

return [

    /*
    |--------------------------------------------------------------------------
    | max login attempt
    |--------------------------------------------------------------------------
    |
    | This value is the threshold on how many attempts the user can try to  login
    |
    */

    'max_log_attempt' => env('MAX_LOGIN_ATTEMPT', 3),

    'auth_banned_minutes' => env('AUTH_BANNED_MINUTES', 10)

];
