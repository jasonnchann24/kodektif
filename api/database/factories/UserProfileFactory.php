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
            'about' => $this->faker->realText(),
            'facebook_link' => $this->generateLink(),
            'linkedin_link' => $this->generateLink(),
            'github_link' => $this->generateLink(),
            'others_link' => $this->generateLink(),
        ];
    }

    protected function generateLink()
    {
        $bool = (bool)random_int(0, 1);
        return $bool ? $this->faker->url : null;
    }
}
