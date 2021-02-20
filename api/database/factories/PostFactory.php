<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(2);
        $slug = Str::slug($title, '-');

        $langs = Language::all();
        if ($langs->count() < 1) {
            $lang = Language::create(
                [
                    'iso_639_1' => 'en',
                    'name' => 'English',
                    'slug' => 'english'
                ]
            );
        } else {
            $lang = $langs->random(1)->first();
        }

        return [
            'language_id' => $lang->id,
            'title' => $title,
            'description' => $this->faker->sentence(10),
            'body' => $this->faker->randomHtml(),
            'slug' => $slug,
            'downvote_count' => 0,
            'upvote_count' => 0
        ];
    }
}
