<?php

namespace App\Http\Resources\Post\PostComment;

use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentVoteResource extends JsonResource
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
            'post_comment_id' => $this->post_comment_id,
            'upvote' => (bool)$this->upvote
        ];
    }
}
