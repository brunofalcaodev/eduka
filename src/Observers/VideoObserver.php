<?php

namespace Eduka\Observers;

use Eduka\Models\Video;

class VideoObserver
{
    /**
     * Handle the video "created" event.
     *
     * @param  \Eduka\Video  $video
     * @return void
     */
    public function saving(Video $video)
    {
        // update index in case it doesn't exist.
        if (blank($video->index) && filled($video->chapter_id)) {
            $video->index = Video::selectRaw('ifnull(max(`index`),0)+1 maxindex')->where('chapter_id', $video->chapter_id)->first()->maxindex;
        }
    }

    /**
     * Handle the video "saved" event.
     *
     * @param  \Eduka\Video  $video
     * @return void
     */
    public function saved(Video $video)
    {
        //
    }

    /**
     * Handle the video "created" event.
     *
     * @param  \Eduka\Video  $video
     * @return void
     */
    public function created(Video $video)
    {
        //
    }

    /**
     * Handle the video "updated" event.
     *
     * @param  \Eduka\Video  $video
     * @return void
     */
    public function updated(Video $video)
    {
        //
    }

    /**
     * Handle the video "deleted" event.
     *
     * @param  \Eduka\Video  $video
     * @return void
     */
    public function deleted(Video $video)
    {
        //
    }

    /**
     * Handle the video "restored" event.
     *
     * @param  \Eduka\Video  $video
     * @return void
     */
    public function restored(Video $video)
    {
        //
    }

    /**
     * Handle the video "force deleted" event.
     *
     * @param  \Eduka\Video  $video
     * @return void
     */
    public function forceDeleted(Video $video)
    {
        //
    }
}
