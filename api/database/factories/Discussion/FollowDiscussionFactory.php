<?php

namespace Database\Factories\Discussion;

use App\Models\Discussion\FollowDiscussion;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowDiscussionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FollowDiscussion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 0,
            'discussion_id' => 0
        ];
    }
}
