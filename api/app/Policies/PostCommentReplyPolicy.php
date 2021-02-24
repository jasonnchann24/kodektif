<?php

namespace App\Policies;

use App\Models\Post\PostComment\PostCommentReply;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostCommentReplyPolicy
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
     * @param  \App\Models\Post\PostComment\PostCommentReply  $postCommentReply
     * @return mixed
     */
    public function view(User $user, PostCommentReply $postCommentReply)
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
     * @param  \App\Models\Post\PostComment\PostCommentReply  $postCommentReply
     * @return mixed
     */
    public function update(User $user, PostCommentReply $postCommentReply)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post\PostComment\PostCommentReply  $postCommentReply
     * @return mixed
     */
    public function delete(User $user, PostCommentReply $postCommentReply)
    {
        return $user->id == $postCommentReply->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post\PostComment\PostCommentReply  $postCommentReply
     * @return mixed
     */
    public function restore(User $user, PostCommentReply $postCommentReply)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post\PostComment\PostCommentReply  $postCommentReply
     * @return mixed
     */
    public function forceDelete(User $user, PostCommentReply $postCommentReply)
    {
        //
    }
}
