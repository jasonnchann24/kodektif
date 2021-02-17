<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(2);
        $slug = Str::slug($title, '-');

        $lang = Language::all()->random(1)->first();
        if (!$lang) {
            $lang = Language::create(
                [
                    'iso_639_1' => 'en',
                    'name' => 'English',
                    'slug' => 'english'
                ]
            );
        }

        return [
            'language_id' => $lang->id,
            'title' => $title,
            'description' => $this->faker->sentence(10),
            'body' => $this->faker->randomHtml(),
            'slug' => $slug,
            'likes_count' => 0
        ];
    }
}
