<?php

namespace Database\Seeders\User;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()
            ->count(50)
            ->create();

        foreach ($users as $user) {
            $createProfile = (bool)random_int(0, 1);
            $suspended = random_int(0, 5);
            if ($createProfile) {
                $user->profile()->save(
                    UserProfile::factory()
                        ->makeOne()
                );
            }

            if ($suspended == 1) {
                $user->is_suspended = true;
                $user->save();
            }
        }
    }
}
