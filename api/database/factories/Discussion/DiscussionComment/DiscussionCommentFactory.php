<?php

namespace Database\Factories\Discussion\DiscussionComment;

use App\Models\Discussion\DiscussionComment\DiscussionComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscussionCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiscussionComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 0,
            'discussion_id' => 0,
            'body' => $this->faker->randomHtml(),
            'mentions' => '[json_array_user_id]',
            'upvote_count' => 0,
            'downvote_count' => 0
        ];
    }
}
