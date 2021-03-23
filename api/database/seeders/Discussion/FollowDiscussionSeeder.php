<?php

namespace Database\Seeders\Discussion;

use App\Models\Discussion\DiscussionComment\DiscussionComment;
use App\Models\Discussion\DiscussionComment\DiscussionCommentReply;
use App\Models\Discussion\FollowDiscussion;
use Illuminate\Database\Seeder;

class FollowDiscussionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = DiscussionComment::all();
        $commentReplies = DiscussionCommentReply::all();

        $needsToFollow = [];

        foreach ($comments as $comment) {
            $userId = $comment->user_id;
            $discussionId = $comment->discussion_id;

            array_push($needsToFollow, [
                'user_id' => $userId,
                'discussion_id' => $discussionId
            ]);
        }

        foreach ($commentReplies as $reply) {
            $userId = $reply->user_id;
            $discussionId = $reply->discussionComment->discussion_id;

            array_push($needsToFollow, [
                'user_id' => $userId,
                'discussion_id' => $discussionId
            ]);
        }

        foreach ($needsToFollow as $follow) {
            FollowDiscussion::firstOrCreate([
                'user_id' => $follow['user_id'],
                'discussion_id' => $follow['discussion_id']
            ]);
        }
    }
}
