<?php

namespace App\Policies;

use App\Models\Discussion\DiscussionComment\DiscussionCommentVote;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscussionCommentVotePolicy
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
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return mixed
     */
    public function view(User $user, DiscussionCommentVote $discussionCommentVote)
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
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return mixed
     */
    public function update(User $user, DiscussionCommentVote $discussionCommentVote)
    {
        return $user->id == $discussionCommentVote->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return mixed
     */
    public function delete(User $user, DiscussionCommentVote $discussionCommentVote)
    {
        return $user->id == $discussionCommentVote->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return mixed
     */
    public function restore(User $user, DiscussionCommentVote $discussionCommentVote)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Discussion\DiscussionComment\DiscussionCommentVote  $discussionCommentVote
     * @return mixed
     */
    public function forceDelete(User $user, DiscussionCommentVote $discussionCommentVote)
    {
        //
    }
}
