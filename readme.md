# About Eduka
Eduka is an opensource course management platform that you can use if you are
making courses and you need a course platform without having to build everything
from scratch.

# Installation

First remove all the non-used packages from your Laravel application, like
sanctum, laravel sail, and for debugging use the filp/whoops instead of the
default one.

Second, after installing this package, run the command:

php artisan vendor:publish --all --force
php artisan vendor:publish --provider=Eduka\Providers\EdukaServiceProvider --force


