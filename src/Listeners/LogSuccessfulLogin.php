<?php

namespace Eduka\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class LogSuccessfulLogin
{
    public $request;

    /**
     * Create the event listener.
     *
     * @param  Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        // Add last ip to the properties json data column.
        $properties = [];
        $properties['last_ip'] = request()->ip();
        $properties['last_login_at'] = date('Y-m-d H:i:s');
        $user->properties = array_merge($user->properties ?? [], $properties);
        $user->last_login_at = date('Y-m-d H:i:s');

        $user->save();
    }
}
