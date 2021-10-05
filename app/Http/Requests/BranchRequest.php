<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
						'client_uuid' => 'required',
            'branch_code' => 'required|string|unique:branches,branch_code'.$this->id,
						'branch_name' => 'required',
						'branch_address' => 'required',
						'area_uuid' => 'string',
						'region_uuid' => 'string',
        ];
    }
}
