<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
            "email" => "required|exists:users,email",
            "url"   => "required"
        ];
    }


    public function messages()
    {
        return [
            "email.required" => "Please enter you email address",
            "email.exists" => "Sorry your email does not exists."
        ];
    }
}
