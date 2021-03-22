<?php

namespace App\Http\Resources\Discussion;

use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscussionResource extends JsonResource
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
            'title' => $this->title,
            'language' => $this->language,
            'body' => $this->body,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'upvote_count' => $this->upvote_count,
            'downvote_count' => $this->downvote_count,
            'author' => $this->user->name,
            'categories' => CategoryResource::collection($this->categories),
            'has_voted' => $this->has_voted,
            // 'discussion_comments' => DiscussionCommentResource::collection(
            //     $this->discussionComments()->paginate(10)
            // )
        ];
    }
}
