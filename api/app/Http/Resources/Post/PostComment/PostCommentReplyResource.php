<?php

namespace App\Http\Resources\Post\PostComment;

use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentReplyResource extends JsonResource
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
