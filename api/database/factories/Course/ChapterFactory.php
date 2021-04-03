<?php

namespace Database\Factories\Course;

use App\Models\Course\Chapter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chapter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(2);
        $slug = Str::slug($title, '-');

        return [
            'course_id' => 0,
            'order' => 1,
            'title' => $title,
            'slug' => $slug
        ];
    }
}
