<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            "client_uuid" => $this->client_uuid,
            "client" => $this->client->client_name,
            "account_pin" => $this->account_pin,
            "full_name" => $this->full_name,
            "mobile_no" => $this->mobile_no,
            "photo" => $this->photo
        ];
    }
}
