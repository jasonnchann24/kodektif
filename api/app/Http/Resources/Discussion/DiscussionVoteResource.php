<?php

namespace App\Http\Resources\Discussion;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscussionVoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
