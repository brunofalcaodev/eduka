<?php

namespace Eduka\Observers;

use Eduka\Models\Chapter;

class ChapterObserver
{
    /**
     * Handle the chapter "saving" event.
     *
     * @param  \Eduka\Chapter  $chapter
     * @return void
     */
    public function saving(Chapter $chapter)
    {
        // update index in case it doesn't exist.
        if (blank($chapter->index)) {
            $chapter->index = Chapter::selectRaw('ifnull(max(`index`),0)+1 maxindex')->first()->maxindex;
        }
    }

    /**
     * Handle the chapter "saved" event.
     *
     * @param  \Eduka\Chapter  $chapter
     * @return void
     */
    public function saved(Chapter $chapter)
    {
        //
    }

    /**
     * Handle the chapter "created" event.
     *
     * @param  \Eduka\Chapter  $chapter
     * @return void
     */
    public function created(Chapter $chapter)
    {
        //
    }

    /**
     * Handle the chapter "updated" event.
     *
     * @param  \Eduka\Chapter  $chapter
     * @return void
     */
    public function updated(Chapter $chapter)
    {
        //
    }

    /**
     * Handle the chapter "deleted" event.
     *
     * @param  \Eduka\Chapter  $chapter
     * @return void
     */
    public function deleted(Chapter $chapter)
    {
        //
    }

    /**
     * Handle the chapter "restored" event.
     *
     * @param  \Eduka\Chapter  $chapter
     * @return void
     */
    public function restored(Chapter $chapter)
    {
        //
    }

    /**
     * Handle the chapter "force deleted" event.
     *
     * @param  \Eduka\Chapter  $chapter
     * @return void
     */
    public function forceDeleted(Chapter $chapter)
    {
        //
    }
}
