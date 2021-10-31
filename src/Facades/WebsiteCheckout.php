<?php

namespace Eduka\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Brunocfalcao\Boilerplate\Skeleton\SkeletonClass
 */
class WebsiteCheckout extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'website-checkout';
    }
}
