<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'is_suspended' => $this->is_suspended,
            'roles' => RoleResource::collection($this->roles),
            'provider' => new ProviderResource($this->whenLoaded('provider'))
        ];
    }
}
