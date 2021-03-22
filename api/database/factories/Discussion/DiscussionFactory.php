<?php

namespace Database\Factories\Discussion;

use App\Models\Discussion\Discussion;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DiscussionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Discussion::class;

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
            'body' => $this->faker->randomHtml(),
            'slug' => $slug,
            'downvote_count' => 0,
            'upvote_count' => 0
        ];
    }
}
