<?php

namespace Eduka\Macros;

use Illuminate\Http\Request;

Request::macro('ip2', function (): string {
    return env('IP_SIMULATION') != null ? env('IP_SIMULATION') : $this->ip();
});
