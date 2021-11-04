<?php

use Eduka\Models\Course;

/**
 * Quick shortcut alias for the course instance.
 *
 * @return \Eduka\Models\Course
 */
function course()
{
    return Course::first();
}
