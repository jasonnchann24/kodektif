<?php

namespace Database\Factories\Course;

use App\Models\Course\ChapterAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapterAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChapterAnswer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 0,
            'chapter_id' => 0,
            'answer' => 'console.log("test")'
        ];
    }
}
