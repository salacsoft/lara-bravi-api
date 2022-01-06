<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountManagerRequest extends FormRequest
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
    public function rules($id = null)
    {
        return [
            "first_name" => "required",
            "account_pin" => "required|min:4",
            "account_code" => "required|unique:account_managers,account_code,".$id,
            "mobile_no" => "required|unique:account_managers,mobile_no,".$id,
        ];
    }
}
