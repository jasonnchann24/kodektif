<?php

namespace Database\Factories\Discussion;

use App\Models\Discussion\DiscussionVote;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscussionVoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiscussionVote::class;

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
            'upvote' => 0
        ];
    }
}
