<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        return Auth::check() ? $next($request) : dd(null);
        $token = $request->header('Authorization'); // o cualquier otro parámetro

        // extraer token si viene como "Bearer TOKEN"
        $token = str_replace('Bearer ', '', $token);

        $usuario = Usuario::where('remember_token', $token)->first();

        

        // guardar usuario en request
        $request->merge(['usuario_logueado' => $usuario]);
        
        return $next($request);
    }
}
