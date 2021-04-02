<?php

namespace Database\Factories\Course;

use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

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
            'user_id' => 0,
            'title' => $title,
            'description' => $this->faker->sentence(10),
            'slug' => $slug,
            'chapter_count' => 0
        ];
    }
}
