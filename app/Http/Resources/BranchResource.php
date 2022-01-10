<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
                'id'   => $this->id,
                'uuid' => $this->uuid,
                'client_uuid' => $this->client_uuid,
                'client_name' => $this->client->client_name,
                'branch_code' => $this->branch_code,
                'branch_name' => $this->branch_name,
                'branch_address' => $this->branch_address,
                "created_at"    => date_format($this->created_at, "Y-m-d h:i:s A"),
                "updated_at"    => date_format($this->updated_at, "Y-m-d h:i:s A"),
                "deleted_at"    => $this->deleted_at ? date_format($this->deleted_at, "Y-m-d h:i:s A") : null,
			];
    }
}
