<?php

namespace Database\Seeders\Discussion;

use App\Models\Discussion\Discussion;
use App\Models\Discussion\DiscussionVote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DiscussionVoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $dicsussions = Discussion::all();

        foreach ($users as $user) {
            $randomDiscussions = $dicsussions->random(random_int(1, 5));

            foreach ($randomDiscussions as $randomDiscussion) {
                $voteCount = DiscussionVote::where('user_id', $user->id)
                    ->where('discussion_id', $randomDiscussion->id)
                    ->count();

                if ($voteCount < 1) {
                    $randomVote = (bool)random_int(0, 1);

                    DiscussionVote::factory([
                        'user_id' => $user->id,
                        'discussion_id' => $randomDiscussion->id,
                        'upvote' => $randomVote
                    ])->create();

                    $dicsussion = $dicsussions->find($randomDiscussion->id);

                    $randomVote ?
                        $dicsussion->upvote_count = $dicsussion->upvote_count + 1 :
                        $dicsussion->downvote_count = $dicsussion->downvote_count + 1;

                    $dicsussion->save();
                }
            }
        }
    }
}
