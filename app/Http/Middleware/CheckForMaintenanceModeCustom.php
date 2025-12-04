<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;

class CheckForMaintenanceModeCustom extends CheckForMaintenanceMode
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        'maintenance/up',
    ];
}

