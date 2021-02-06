<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'user_id' => $this->user_id,
            'country' => $this->country,
            'about' => $this->about,
            'facebook_link' => $this->facebook_link,
            'linkedin_link' => $this->linkedin_link,
            'github_link' => $this->github_link,
            'others_link' => $this->others_link,
        ];
    }
}
