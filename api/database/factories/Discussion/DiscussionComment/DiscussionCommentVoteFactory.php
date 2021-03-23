<?php

namespace Database\Factories\Discussion\DiscussionComment;

use App\Models\Discussion\DiscussionComment\DiscussionCommentVote;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscussionCommentVoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiscussionCommentVote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 0,
            'discussion_comment_id' => 0,
            'upvote' => 0
        ];
    }
}
