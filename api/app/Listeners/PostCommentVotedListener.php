<?php

namespace App\Listeners;

use App\Events\PostCommentVotedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PostCommentVotedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostCommentVotedEvent  $event
     * @return void
     */
    public function handle(PostCommentVotedEvent $event)
    {
        $comment = $event->comment;
        $upVote = $event->upVote;

        $upVote ?
            $comment->upvote_count = $comment->upvote_count + 1 :
            $comment->downvote_count = $comment->downvote_count + 1;

        $comment->save();
    }
}
