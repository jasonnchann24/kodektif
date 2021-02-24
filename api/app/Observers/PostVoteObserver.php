<?php

namespace App\Observers;

use App\Events\PostVotedEvent;
use App\Models\Post\Post;
use App\Models\Post\PostVote;

class PostVoteObserver
{
    /**
     * Handle the PostVote "created" event.
     *
     * @param  \App\Models\PostVote  $postVote
     * @return void
     */
    public function created(PostVote $postVote)
    {
        $post = Post::find($postVote->post_id);
        PostVotedEvent::dispatch($post, (bool)$postVote->upvote);
    }

    /**
     * Handle the PostVote "updated" event.
     *
     * @param  \App\Models\PostVote  $postVote
     * @return void
     */
    public function updated(PostVote $postVote)
    {
        $direction = $postVote->upvote;
        $post = Post::find($postVote->post_id);
        if ($direction) {
            $post->upvote_count += 1;
            $post->downvote_count -= 1;
        } else {
            $post->downvote_count += 1;
            $post->upvote_count -= 1;
        }
        $post->save();
    }

    /**
     * Handle the PostVote "deleted" event.
     *
     * @param  \App\Models\PostVote  $postVote
     * @return void
     */
    public function deleted(PostVote $postVote)
    {
        $direction = $postVote->upvote ? 'up' : 'down';
        $post = Post::find($postVote->post_id);
        $post->{$direction . 'vote_count'} -= 1;
        $post->save();
    }

    /**
     * Handle the PostVote "restored" event.
     *
     * @param  \App\Models\PostVote  $postVote
     * @return void
     */
    public function restored(PostVote $postVote)
    {
        //
    }

    /**
     * Handle the PostVote "force deleted" event.
     *
     * @param  \App\Models\PostVote  $postVote
     * @return void
     */
    public function forceDeleted(PostVote $postVote)
    {
        //
    }
}
