<?php

namespace Database\Factories\Discussion\DiscussionComment;

use App\Models\Discussion\DiscussionComment\DiscussionCommentReply;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscussionCommentReplyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiscussionCommentReply::class;

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
            'body' => $this->faker->randomHtml(),
            'mentions' => '[json_array_user_id]',
        ];
    }
}
