<?php

namespace Database\Seeders\Post\PostComment;

use App\Models\Post\PostComment\PostComment;
use App\Models\Post\PostComment\PostCommentVote;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostCommentVoteSeeder extends Seeder
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

        foreach ($users as $user) {
            $randomPostComments = $postComments->random(random_int(1, 5));

            foreach ($randomPostComments as $randomPostComment) {
                $voteCount = PostCommentVote::where('user_id', $user->id)
                    ->where('post_comment_id', $randomPostComment->id)
                    ->count();

                if ($voteCount < 1) {
                    $randomVote = (bool)random_int(0, 1);

                    PostCommentVote::factory([
                        'user_id' => $user->id,
                        'post_comment_id' => $randomPostComment->id,
                        'upvote' => $randomVote
                    ])->create();

                    $postComment = $postComments->find($randomPostComment->id);

                    $randomVote ?
                        $postComment->upvote_count = $postComment->upvote_count + 1 :
                        $postComment->downvote_count = $postComment->downvote_count + 1;

                    $postComment->save();
                }
            }
        }
    }
}
