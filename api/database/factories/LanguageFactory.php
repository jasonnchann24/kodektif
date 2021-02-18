<?php

namespace Database\Factories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LanguageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Language::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $code = $this->faker->languageCode . Str::random(2);
        $name = $code . " name";
        return [
            'name' => $name,
            'iso_639_1' => $code,
            'slug' => str_replace(' ', '-', $name)
        ];
    }
}
