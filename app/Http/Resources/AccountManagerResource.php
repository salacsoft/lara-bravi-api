<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use URL;

class AccountManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "account_pin" => $this->account_pin,
            "account_code" => $this->account_code,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "middle_name" => $this->middle_name,
            "full_name" => $this->full_name,
            "mobile_no" => $this->mobile_no,
            "photo" => URL::to("/") ."/".$this->photo,
            "created_at" => date_format($this->created_at,"Y-M-d H:i:s a"),
            "updated_at" => date_format($this->updated_at,"Y-M-d H:i:s a"),
            "deleted_at" => $this->deleted_at ? date_format($this->updated_at,"Y-M-d H:i:s a") : null
        ];
    }
}
