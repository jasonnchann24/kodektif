<?php

namespace Database\Factories\Post\PostComment;

use App\Models\Post\PostComment\PostCommentReply;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentReplyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostCommentReply::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 0,
            'post_comment_id' => 0,
            'body' => $this->faker->randomHtml(),
            'mentions' => '[json_array_user_id]',
        ];
    }
}
