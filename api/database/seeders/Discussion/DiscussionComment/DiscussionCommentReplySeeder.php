<?php

namespace Database\Seeders\Discussion\DiscussionComment;

use App\Models\Discussion\DiscussionComment\DiscussionComment;
use App\Models\Discussion\DiscussionComment\DiscussionCommentReply;
use App\Models\User;
use Illuminate\Database\Seeder;

class DiscussionCommentReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = User::all();
        $discussionComments = DiscussionComment::all();

        for ($i = 0; $i < 100; $i++) {
            $randomUser = $users->random(1)->first();
            $randomComments = $discussionComments->random(random_int(1, 5));

            foreach ($randomComments as $randomComment) {
                $randomMentions = $users->random(random_int(0, 2))
                    ->pluck('id')
                    ->toArray();
                $json = json_encode($randomMentions);
                DiscussionCommentReply::factory([
                    'user_id' => $randomUser->id,
                    'discussion_comment_id' => $randomComment->id,
                    'mentions' => $json
                ])->create();
            }
        }
    }
}
