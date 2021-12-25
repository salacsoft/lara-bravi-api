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
                'uuid' => $this->uuid,
                'branch_code' => $this->branch_code,
                'branch_name' => $this->branch_name,
                'branch_address' => $this->branch_address,
			];
    }
}
