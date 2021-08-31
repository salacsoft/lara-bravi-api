<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CquoinUserRequest extends FormRequest
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
            "first_name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:4",
            "confirm_password" => "required|same:password"
            // "photo" => "sometimes|mimes:jpeg,jpg,png,gif|required|max:200"
        ];
    }
}
