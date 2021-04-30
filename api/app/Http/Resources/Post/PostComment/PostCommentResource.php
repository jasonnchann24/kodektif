<?php

namespace App\Http\Resources\Post\PostComment;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'user_id' => $this->user_id,
            'post_id' => $this->post_id,
            'body' => $this->body,
            'mentions' => $this->mentions,
            'upvote_count' => $this->upvote_count,
            'downvote_count' => $this->downvote_count,
            'created_at' => $this->created_at,
            'has_voted' => $this->has_voted,
            'replies' => PostCommentReplyResource::collection(
                $this->postCommentReplies
            )
        ];
    }
}
