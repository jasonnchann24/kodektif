<?php

namespace App\Policies;

use App\Models\Discussion\DiscussionComment\DiscussionCommentReply;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscussionCommentReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentReply  $discussionCommentReply
     * @return mixed
     */
    public function view(User $user, DiscussionCommentReply $discussionCommentReply)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentReply  $discussionCommentReply
     * @return mixed
     */
    public function update(User $user, DiscussionCommentReply $discussionCommentReply)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentReply  $discussionCommentReply
     * @return mixed
     */
    public function delete(User $user, DiscussionCommentReply $discussionCommentReply)
    {
        return $user->id == $discussionCommentReply->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentReply  $discussionCommentReply
     * @return mixed
     */
    public function restore(User $user, DiscussionCommentReply $discussionCommentReply)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentReply  $discussionCommentReply
     * @return mixed
     */
    public function forceDelete(User $user, DiscussionCommentReply $discussionCommentReply)
    {
        //
    }
}
