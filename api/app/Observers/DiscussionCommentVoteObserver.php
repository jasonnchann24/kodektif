<?php

namespace App\Observers;

use App\Events\DiscussionCommentVotedEvent;
use App\Models\Discussion\DiscussionComment\DiscussionComment;
use App\Models\Discussion\DiscussionComment\DiscussionCommentVote;

class DiscussionCommentVoteObserver
{
    /**
     * Handle the DiscussionCommentVote "created" event.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return void
     */
    public function created(DiscussionCommentVote $discussionCommentVote)
    {
        $comment = DiscussionComment::find($discussionCommentVote->discussion_comment_id);
        DiscussionCommentVotedEvent::dispatch($comment, (bool)$discussionCommentVote->upvote);
    }

    /**
     * Handle the DiscussionCommentVote "updated" event.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return void
     */
    public function updated(DiscussionCommentVote $discussionCommentVote)
    {
        $direction = $discussionCommentVote->upvote;
        $comment = DiscussionComment::find($discussionCommentVote->discussion_comment_id);
        if ($direction) {
            $comment->upvote_count += 1;
            $comment->downvote_count -= 1;
        } else {
            $comment->downvote_count += 1;
            $comment->upvote_count -= 1;
        }
        $comment->save();
    }

    /**
     * Handle the DiscussionCommentVote "deleted" event.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return void
     */
    public function deleted(DiscussionCommentVote $discussionCommentVote)
    {

        $direction = $discussionCommentVote->upvote ? 'up' : 'down';
        $post = DiscussionComment::find($discussionCommentVote->discussion_comment_id);
        $post->{$direction . 'vote_count'} -= 1;
        $post->save();
    }

    /**
     * Handle the DiscussionCommentVote "restored" event.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return void
     */
    public function restored(DiscussionCommentVote $discussionCommentVote)
    {
        //
    }

    /**
     * Handle the DiscussionCommentVote "force deleted" event.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return void
     */
    public function forceDeleted(DiscussionCommentVote $discussionCommentVote)
    {
        //
    }
}
