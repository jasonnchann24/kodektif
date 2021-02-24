<?php

namespace Database\Seeders\Post;

use App\Models\Category;
use App\Models\Post\Post;
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

        $posts = Post::all();
        $categories = Category::where('parent_id', '!=', null)->get();
        foreach ($posts as $post) {
            $randomCategories = $categories
                ->random(random_int(1, 2))
                ->pluck('id')
                ->toArray();
            $post->categories()->sync($randomCategories);
        }
    }
}
