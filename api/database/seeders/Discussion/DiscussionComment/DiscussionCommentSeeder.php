<?php

namespace Database\Seeders\Discussion\DiscussionComment;

use App\Models\Discussion\Discussion;
use App\Models\Discussion\DiscussionComment\DiscussionComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DiscussionCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $discussions = Discussion::all();

        for ($i = 0; $i < 100; $i++) {
            $randomUser = $users->random(1)->first();
            $randomDiscussions = $discussions->random(random_int(1, 5));

            foreach ($randomDiscussions as $randomDiscussion) {
                $randomMentions = $users->random(random_int(0, 2))
                    ->pluck('id')
                    ->toArray();
                $json = json_encode($randomMentions);
                DiscussionComment::factory([
                    'user_id' => $randomUser->id,
                    'discussion_id' => $randomDiscussion->id,
                    'mentions' => $json
                ])->create();
            }
        }
    }
}
