<?php

namespace Eduka\Observers;

use Eduka\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the user "saving" event.
     *
     * @param  \Eduka\User  $user
     * @return void
     */
    public function saving(User $user)
    {
        if (is_null($user->uuid)) {
            $user->uuid = (string) Str::uuid();
        }
    }

    /**
     * Handle the user "created" event.
     *
     * @param  \Eduka\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \Eduka\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \Eduka\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \Eduka\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \Eduka\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
