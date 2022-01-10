<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            "id"    => $this->id,
            "uuid"  => $this->uuid,
            "company_uuid"  => $this->company_uuid,
            "client_code"   => $this->client_code,
            "client_name"   => $this->client_name,
            "client_address"=> $this->client_address,
            "is_active" => $this->is_active,
            "created_at"    => date_format($this->created_at, "Y-m-d h:i:s A"),
            "updated_at"    => date_format($this->updated_at, "Y-m-d h:i:s A"),
            "deleted_at"    => $this->deleted_at ? date_format($this->deleted_at, "Y-m-d h:i:s A") : null,
        ];
    }
}
