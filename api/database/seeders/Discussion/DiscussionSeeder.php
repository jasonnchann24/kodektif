<?php

namespace Database\Seeders\Discussion;

use App\Models\Category;
use App\Models\Discussion\Discussion;
use App\Models\User;
use Illuminate\Database\Seeder;

class DiscussionSeeder extends Seeder
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
            Discussion::factory()
                ->count(random_int(1, 3))
                ->for($member)
                ->create();
        }

        $discussions = Discussion::all();
        $categories = Category::where('parent_id', '!=', null)->get();
        foreach ($discussions as $discussion) {
            $randomCategories = $categories
                ->random(random_int(1, 2))
                ->pluck('id')
                ->toArray();
            $discussion->categories()->sync($randomCategories);
        }
    }
}
