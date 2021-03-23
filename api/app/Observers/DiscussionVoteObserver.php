<?php

namespace App\Observers;

use App\Events\DiscussionVotedEvent;
use App\Models\Discussion\Discussion;
use App\Models\Discussion\DiscussionVote;

class DiscussionVoteObserver
{
    /**
     * Handle the DiscussionVote "created" event.
     *
     * @param  \App\Models\Discussion\DiscussionVote  $discussionVote
     * @return void
     */
    public function created(DiscussionVote $discussionVote)
    {
        $discussion = Discussion::find($discussionVote->discussion_id);
        DiscussionVotedEvent::dispatch($discussion, (bool)$discussionVote->upvote);
    }

    /**
     * Handle the DiscussionVote "updated" event.
     *
     * @param  \App\Models\Discussion\DiscussionVote  $discussionVote
     * @return void
     */
    public function updated(DiscussionVote $discussionVote)
    {
        $direction = $discussionVote->upvote;
        $discussion = Discussion::find($discussionVote->discussion_id);
        if ($direction) {
            $discussion->upvote_count += 1;
            $discussion->downvote_count -= 1;
        } else {
            $discussion->downvote_count += 1;
            $discussion->upvote_count -= 1;
        }
        $discussion->save();
    }

    /**
     * Handle the DiscussionVote "deleted" event.
     *
     * @param  \App\Models\Discussion\DiscussionVote  $discussionVote
     * @return void
     */
    public function deleted(DiscussionVote $discussionVote)
    {
        $direction = $discussionVote->upvote ? 'up' : 'down';
        $discussion = Discussion::find($discussionVote->discussion_id);
        $discussion->{$direction . 'vote_count'} -= 1;
        $discussion->save();
    }

    /**
     * Handle the DiscussionVote "restored" event.
     *
     * @param  \App\Models\Discussion\DiscussionVote  $discussionVote
     * @return void
     */
    public function restored(DiscussionVote $discussionVote)
    {
        //
    }

    /**
     * Handle the DiscussionVote "force deleted" event.
     *
     * @param  \App\Models\Discussion\DiscussionVote  $discussionVote
     * @return void
     */
    public function forceDeleted(DiscussionVote $discussionVote)
    {
        //
    }
}
