<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\Post\PostComment\PostCommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'description' => $this->description,
            'language' => $this->language,
            'body' => $this->body,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'upvote_count' => $this->upvote_count,
            'downvote_count' => $this->downvote_count,
            'author' => $this->user->name,
            'categories' => CategoryResource::collection($this->categories),
            'has_voted' => $this->has_voted,
            'post_comments' => PostCommentResource::collection(
                $this->postComments()->paginate(10)
            )
        ];
    }
}
