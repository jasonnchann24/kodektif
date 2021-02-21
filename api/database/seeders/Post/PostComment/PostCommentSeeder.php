<?php

namespace Database\Seeders\Post\PostComment;

use App\Models\Post;
use App\Models\Post\PostComment\PostComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $posts = Post::all();

        for ($i = 0; $i < 100; $i++) {
            $randomUser = $users->random(1)->first();
            $randomPosts = $posts->random(random_int(1, 5));

            foreach ($randomPosts as $randomPost) {
                $randomMentions = $users->random(random_int(0, 2))
                    ->pluck('id')
                    ->toArray();
                $json = json_encode($randomMentions);
                PostComment::factory([
                    'user_id' => $randomUser->id,
                    'post_id' => $randomPost->id,
                    'mentions' => $json
                ])->create();
            }
        }
    }
}
