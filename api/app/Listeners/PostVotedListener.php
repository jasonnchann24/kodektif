<?php

namespace App\Listeners;

use App\Events\PostVotedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PostVotedListener implements ShouldQueue
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
     * @param  PostVotedEvent  $event
     * @return void
     */
    public function handle(PostVotedEvent $event)
    {
        $post = $event->post;
        $upVote = $event->upVote;

        $upVote ?
            $post->upvote_count = $post->upvote_count + 1 :
            $post->downvote_count = $post->downvote_count + 1;

        $post->save();
    }
}
