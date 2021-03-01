<?php

namespace App\Http\Resources\Article;

use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'likes_count' => $this->likes_count,
            'author' => $this->user->name,
            'categories' => CategoryResource::collection($this->categories),
            'has_liked' => $this->has_liked,
        ];
    }
}
