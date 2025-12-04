<?php

namespace App\Http\Middleware;

use Closure;
use Request;

class IpSepd
{
    private $ip_sepd = [
        '127.0.0.1',    // localhost
        '80.26.59.145', // 3ways
        '81.45.42.164'  // sepd
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // comprobar que la ip es la de SEPD
        if (in_array(Request::ip(), $this->ip_sepd)) {
            return $next($request);
        } else {
            abort(404);
        }
    }
}
