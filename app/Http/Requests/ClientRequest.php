<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            "client_code"   => "required|unique:clients,client_code,".$this->id,
            "client_name"   => "required",
            // "account_manager_uuid" => "required|exists:account_managers,uuid",
            // "company_uuid"   => "required|exists:companies,uuid"
        ];
    }

    public function messages()
    {
        return [
            "client_code.required"  => "Please provide client code.",
            "client_code.unique"    => "Sorry client code already in used, please try other code.",
            "client_name.required"  => "Please provide client name.",
            // "account_manager_uuid.required"  => "Please provide account manager",
            // "account_manager_uuid.exists"   => "Invlaid Account manager, it does not exists in our datatabase",
            // "company_uuid.required" => "Please provide company uuid.",
            // "company_uuid.exists" => "Invalid company, it does not exists"
        ];
    }
}
