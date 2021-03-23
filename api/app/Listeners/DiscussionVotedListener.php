<?php

namespace App\Listeners;

use App\Events\DiscussionVotedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DiscussionVotedListener
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
     * @param  DiscussionVotedEvent  $event
     * @return void
     */
    public function handle(DiscussionVotedEvent $event)
    {
        $discussion = $event->discussion;
        $upVote = $event->upVote;

        $upVote ?
            $discussion->upvote_count = $discussion->upvote_count + 1 :
            $discussion->downvote_count = $discussion->downvote_count + 1;

        $discussion->save();
    }
}
