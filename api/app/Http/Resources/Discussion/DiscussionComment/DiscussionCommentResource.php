<?php

namespace App\Http\Resources\Discussion\DiscussionComment;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscussionCommentResource extends JsonResource
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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name
            ],
            'discussion_id' => $this->discussion_id,
            'body' => $this->body,
            'mentions' => $this->mentions,
            'upvote_count' => $this->upvote_count,
            'downvote_count' => $this->downvote_count,
            'created_at' => $this->created_at,
            'has_voted' => $this->has_voted,
            'replies' => DiscussionCommentReplyResource::collection(
                $this->discussionCommentReplies()->paginate(30)
            )
        ];
    }
}
