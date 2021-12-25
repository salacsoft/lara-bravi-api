<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CquoinResource extends JsonResource
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
            "uui"   => $this->uuid,
            "first_name"    => ucfirst($this->first_name),
            "last_name"     => ucfirst($this->last_name),
            "full_name"     => ucfirst($this->first_name) . " " . ucfirst($this->last_name),
            "email"         => $this->user->email,
            "user_uuid"     => $this->user->uuid,
            "user_id"       => $this->user->id
        ];
    }
}
