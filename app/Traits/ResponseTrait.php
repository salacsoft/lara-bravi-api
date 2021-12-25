<?php

namespace App\Traits;

trait ResponseTrait
{
    protected function success($message = null, $data =[], $status = 200)
    {
        return response()->json([
            "success" => true,
            "data" => $data,
            "message" => $message
        ], $status);
    }


    protected function fail($message = null, $status = 500, $errors= [])
    {
        return response()->json([
            "success" => false,
            "error" => $errors,
            "message" => $message
        ], $status);
    }
}
