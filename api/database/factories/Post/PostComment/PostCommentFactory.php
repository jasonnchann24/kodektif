<?php

namespace Database\Factories\Post\PostComment;

use App\Models\Post\PostComment\PostComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 0,
            'post_id' => 0,
            'body' => $this->faker->randomHtml(),
            'mentions' => '[json_array_user_id]',
            'upvote_count' => 0,
            'downvote_count' => 0
        ];
    }
}
