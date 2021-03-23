<?php

namespace App\Http\Resources\Discussion\DiscussionComment;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscussionCommentVoteResource extends JsonResource
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
            'discussion_comment_id' => $this->discussion_comment_id,
            'upvote' => (bool)$this->upvote
        ];
    }
}
