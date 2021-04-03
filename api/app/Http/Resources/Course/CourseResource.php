<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user' => $this->user,
            'chapter_count' => $this->chapter_count,
            'slug' => $this->slug,
            'chapters' => ChapterResource::collection($this->chapters()->orderBy('order', 'asc')->get()),
            'categories' => CategoryResource::collection($this->categories),
        ];
    }
}
