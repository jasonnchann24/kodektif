<?php

namespace Database\Seeders\Post\PostComment;

use App\Models\Post\PostComment\PostComment;
use App\Models\Post\PostComment\PostCommentReply;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostCommentReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $postComments = PostComment::all();

        for ($i = 0; $i < 100; $i++) {
            $randomUser = $users->random(1)->first();
            $randomComments = $postComments->random(random_int(1, 5));

            foreach ($randomComments as $randomComment) {
                $randomMentions = $users->random(random_int(0, 2))
                    ->pluck('id')
                    ->toArray();
                $json = json_encode($randomMentions);
                PostCommentReply::factory([
                    'user_id' => $randomUser->id,
                    'post_comment_id' => $randomComment->id,
                    'mentions' => $json
                ])->create();
            }
        }
    }
}
