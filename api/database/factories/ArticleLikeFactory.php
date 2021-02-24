<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleLikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArticleLike::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 0,
            'article_id' => 0
        ];
    }
}
