<?php

namespace Eduka\Facades;

use Illuminate\Support\Facades\Facade;

class CourseCheckout extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'course-checkout';
    }
}
