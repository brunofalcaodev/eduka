<?php

namespace Eduka\Observers;

use Eduka\Models\Course;

class CourseObserver
{
    public function saving(Course $course)
    {
        if ($course->isDirty('meta_tags') && !is_null($course->meta_tags) || is_null($course->meta_tags)) {
            $course->meta_tags = [
            // Miscellaneous
            'theme-color' => '',

            // Twitter
            'twitter:title' => '',
            'twitter:site' => '',
            'twitter:description' => '',
            'twitter:creator' => '',

            // Opengraph
            'og:url' => '',
            'og:description' => '',
            'og:title' => '',
            ];
        }
    }

    /**
     * Handle the course "created" event.
     *
     * @param  \Eduka\Course  $course
     * @return void
     */
    public function created(Course $course)
    {
        //
    }

    /**
     * Handle the course "updated" event.
     *
     * @param  \Eduka\Course  $course
     * @return void
     */
    public function updated(Course $course)
    {
        //
    }

    /**
     * Handle the course "deleted" event.
     *
     * @param  \Eduka\Course  $course
     * @return void
     */
    public function deleted(Course $course)
    {
        //
    }

    /**
     * Handle the course "restored" event.
     *
     * @param  \Eduka\Course  $course
     * @return void
     */
    public function restored(Course $course)
    {
        //
    }

    /**
     * Handle the course "force deleted" event.
     *
     * @param  \Eduka\Course  $course
     * @return void
     */
    public function forceDeleted(Course $course)
    {
        //
    }
}
