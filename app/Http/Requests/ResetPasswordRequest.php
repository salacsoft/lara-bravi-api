<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "email" => "required|exists:password_resets,email",
            "token" => "required|exists:password_resets,token",
            "password" => "required|same:confirm_password|min:4|max:30",
            "confirm_password" => "required|min:4|max:30"
        ];
    }


    public function messages()
    {
        return [
            "email.required" => "Bad request, email does not exists on your request",
            "email.exists"   => "Bad request, Invalid email address",
            "token.required" => "Bad request, Token  does not exists on your request",
            "email.exists"   => "Bad request, Token Expired / Invalid please do not modify the token or email on your URL",
            "password.required" => "Bad request, Please include Password on your payload",
            "password.same"     => "Bad request, Password and confirm password  does not mactch",
            "confirm_password.required" => "Bad request, Confirm Please include Confirm Password on your payload"
        ];
    }
}
