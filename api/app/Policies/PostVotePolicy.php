<?php

namespace App\Policies;

use App\Models\Post\PostVote;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostVotePolicy
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
     * @param  \App\Models\PostVote  $postVote
     * @return mixed
     */
    public function view(User $user, PostVote $postVote)
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
     * @param  \App\Models\PostVote  $postVote
     * @return mixed
     */
    public function update(User $user, PostVote $postVote)
    {
        return $user->id == $postVote->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostVote  $postVote
     * @return mixed
     */
    public function delete(User $user, PostVote $postVote)
    {
        return $user->id == $postVote->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostVote  $postVote
     * @return mixed
     */
    public function restore(User $user, PostVote $postVote)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostVote  $postVote
     * @return mixed
     */
    public function forceDelete(User $user, PostVote $postVote)
    {
        //
    }
}
