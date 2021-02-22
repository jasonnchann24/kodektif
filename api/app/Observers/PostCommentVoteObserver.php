<?php

namespace App\Observers;

use App\Events\PostCommentVotedEvent;
use App\Models\Post\PostComment\PostComment;
use App\Models\Post\PostComment\PostCommentVote;

class PostCommentVoteObserver
{
    /**
     * Handle the PostCommentVote "created" event.
     *
     * @param  \App\Models\Post\PostComment\PostCommentVote  $postCommentVote
     * @return void
     */
    public function created(PostCommentVote $postCommentVote)
    {
        $comment = PostComment::find($postCommentVote->post_comment_id);
        PostCommentVotedEvent::dispatch($comment, (bool)$postCommentVote->upvote);
    }

    /**
     * Handle the PostCommentVote "updated" event.
     *
     * @param  \App\Models\Post\PostComment\PostCommentVote  $postCommentVote
     * @return void
     */
    public function updated(PostCommentVote $postCommentVote)
    {
        $direction = $postCommentVote->upvote;
        $comment = PostComment::find($postCommentVote->post_comment_id);
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
     * Handle the PostCommentVote "deleted" event.
     *
     * @param  \App\Models\Post\PostComment\PostCommentVote  $postCommentVote
     * @return void
     */
    public function deleted(PostCommentVote $postCommentVote)
    {
        $direction = $postCommentVote->upvote ? 'up' : 'down';
        $post = PostComment::find($postCommentVote->post_comment_id);
        $post->{$direction . 'vote_count'} -= 1;
        $post->save();
    }

    /**
     * Handle the PostCommentVote "restored" event.
     *
     * @param  \App\Models\Post\PostComment\PostCommentVote  $postCommentVote
     * @return void
     */
    public function restored(PostCommentVote $postCommentVote)
    {
        //
    }

    /**
     * Handle the PostCommentVote "force deleted" event.
     *
     * @param  \App\Models\Post\PostComment\PostCommentVote  $postCommentVote
     * @return void
     */
    public function forceDeleted(PostCommentVote $postCommentVote)
    {
        //
    }
}
