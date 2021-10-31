<?php

namespace Eduka\Policies;

use Eduka\Models\User;
use Eduka\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    public function trashedAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \Eduka\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Eduka\User  $user
     * @param  \Eduka\Video  $video
     * @return mixed
     */
    public function view(User $user, Video $video)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Eduka\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Eduka\User  $user
     * @param  \Eduka\Video  $video
     * @return mixed
     */
    public function update(User $user, Video $video)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Eduka\User  $user
     * @param  \Eduka\Video  $video
     * @return mixed
     */
    public function delete(User $user, Video $video)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Eduka\User  $user
     * @param  \Eduka\Video  $video
     * @return mixed
     */
    public function restore(User $user, Video $video)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Eduka\User  $user
     * @param  \Eduka\Video  $video
     * @return mixed
     */
    public function forceDelete(User $user, Video $video)
    {
        return true;
    }
}
