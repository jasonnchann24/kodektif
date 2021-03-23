<?php

namespace Database\Seeders\Discussion\DiscussionComment;

use App\Models\Discussion\DiscussionComment\DiscussionComment;
use App\Models\Discussion\DiscussionComment\DiscussionCommentVote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DiscussionCommentVoteSeeder extends Seeder
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

        foreach ($users as $user) {
            $randomDiscussionComments = $discussionComments->random(random_int(1, 5));

            foreach ($randomDiscussionComments as $randomDiscussionComment) {
                $voteCount = DiscussionCommentVote::where('user_id', $user->id)
                    ->where('discussion_comment_id', $randomDiscussionComment->id)
                    ->count();

                if ($voteCount < 1) {
                    $randomVote = (bool)random_int(0, 1);

                    DiscussionCommentVote::factory([
                        'user_id' => $user->id,
                        'discussion_comment_id' => $randomDiscussionComment->id,
                        'upvote' => $randomVote
                    ])->create();

                    $discussionComment = $discussionComments->find($randomDiscussionComment->id);

                    $randomVote ?
                        $discussionComment->upvote_count = $discussionComment->upvote_count + 1 :
                        $discussionComment->downvote_count = $discussionComment->downvote_count + 1;

                    $discussionComment->save();
                }
            }
        }
    }
}
