<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $token = $this->createToken($request->email);
        return [
            "id" => $this->userable->id,
            "uuid" => $this->userable->uuid,
            "user_id" => $this->id,
            "user_uuid" => $this->uuid,
            "full_name" => ucfirst($this->userable->first_name) . " " . ucfirst($this->userable->last_name),
            "email" => $this->email,
            "token" => $token->plainTextToken
        ];
    }
}
