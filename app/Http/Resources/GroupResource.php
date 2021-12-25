<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            "uuid"          => $this->uuid,
            "group_name"    => $this->group_name,
            "created_at"    => date_format($this->created_at, "Y-m-d h:i:s A"),
            "updated_at"    => date_format($this->updated_at, "Y-m-d h:i:s A"),
            "deleted_at"    => $this->deleted_at ? date_format($this->deleted_at, "Y-m-d h:i:s A") : null,
        ];
    }
}
