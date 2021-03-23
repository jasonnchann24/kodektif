<?php

namespace App\Listeners;

use App\Events\DiscussionCommentVotedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DiscussionCommentVotedListener
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
     * @param  DiscussionCommentVotedEvent  $event
     * @return void
     */
    public function handle(DiscussionCommentVotedEvent $event)
    {
        $comment = $event->comment;
        $upVote = $event->upVote;

        $upVote ?
            $comment->upvote_count = $comment->upvote_count + 1 :
            $comment->downvote_count = $comment->downvote_count + 1;

        $comment->save();
    }
}
