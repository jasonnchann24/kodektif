<?php

namespace Database\Seeders\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members =  User::whereHas('roles', function ($role) {
            $role->where(
                'name',
                '=',
                config('constants.role_member')
            );
        })->get();

        foreach ($members as $member) {
            Post::factory()
                ->count(random_int(1, 3))
                ->for($member)
                ->create();
        }
    }
}
