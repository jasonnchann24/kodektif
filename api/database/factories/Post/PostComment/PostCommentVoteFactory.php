<?php

namespace Database\Factories\Post\PostComment;

use App\Models\Post\PostComment\PostCommentVote;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentVoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostCommentVote::class;

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
            'upvote' => 0
        ];
    }
}
