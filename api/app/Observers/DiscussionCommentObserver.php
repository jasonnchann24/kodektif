<?php

namespace App\Observers;

use App\Models\Discussion\DiscussionComment\DiscussionComment;
use App\Models\Discussion\FollowDiscussion;

class DiscussionCommentObserver
{
    /**
     * Handle the DiscussionComment "created" event.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionComment  $discussionComment
     * @return void
     */
    public function created(DiscussionComment $discussionComment)
    {
        FollowDiscussion::firstOrCreate([
            'user_id' => $discussionComment->user_id,
            'discussion_id' => $discussionComment->discussion_id
        ]);
    }

    /**
     * Handle the DiscussionComment "updated" event.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionComment  $discussionComment
     * @return void
     */
    public function updated(DiscussionComment $discussionComment)
    {
        //
    }

    /**
     * Handle the DiscussionComment "deleted" event.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionComment  $discussionComment
     * @return void
     */
    public function deleted(DiscussionComment $discussionComment)
    {
        //
    }

    /**
     * Handle the DiscussionComment "restored" event.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionComment  $discussionComment
     * @return void
     */
    public function restored(DiscussionComment $discussionComment)
    {
        //
    }

    /**
     * Handle the DiscussionComment "force deleted" event.
     *
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionComment  $discussionComment
     * @return void
     */
    public function forceDeleted(DiscussionComment $discussionComment)
    {
        //
    }
}
