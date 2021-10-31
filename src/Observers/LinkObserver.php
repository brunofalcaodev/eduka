<?php

namespace Eduka\Observers;

use Eduka\Models\Link;

class LinkObserver
{
    /**
     * Handle the link "created" event.
     *
     * @param  \Eduka\Link  $link
     * @return void
     */
    public function created(Link $link)
    {
        //
    }

    /**
     * Handle the link "updated" event.
     *
     * @param  \Eduka\Link  $link
     * @return void
     */
    public function updated(Link $link)
    {
        //
    }

    /**
     * Handle the link "deleted" event.
     *
     * @param  \Eduka\Link  $link
     * @return void
     */
    public function deleted(Link $link)
    {
        //
    }

    /**
     * Handle the link "restored" event.
     *
     * @param  \Eduka\Link  $link
     * @return void
     */
    public function restored(Link $link)
    {
        //
    }

    /**
     * Handle the link "force deleted" event.
     *
     * @param  \Eduka\Link  $link
     * @return void
     */
    public function forceDeleted(Link $link)
    {
        //
    }
}
