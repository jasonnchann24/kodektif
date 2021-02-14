<?php

namespace Database\Seeders;

use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserProfileSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') != 'production') {
            $this->call(UserProfileSeeder::class);
            $this->call(RoleSeeder::class);
            $this->call(CategorySeeder::class);
            $this->call(LanguageSeeder::class);
        }
    }
}
