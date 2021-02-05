<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition($overrides = [])
    {
        return [
            'country' => $this->faker->countryCode,
            'about' => $this->faker->paragraph(),
            'facebook_link' => $this->faker->url,
            'linkedin_link' => $this->faker->url,
            'github_link' => $this->faker->url,
            'others_link' => $this->faker->url,
        ];
    }
}
