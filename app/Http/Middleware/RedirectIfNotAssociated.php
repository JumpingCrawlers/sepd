<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\LoginController;
use App\UsuarioSocio;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

/**
 * 3ways Euro Fuenmayor
 * Middleware para redirección a la pantalla de login si el usuario es no socio
 */

class RedirectIfNotAssociated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!(Auth::guard($guard)->check())) {
            if(isset($request->all()['url_cid'])){
                session()->put('cid_servicios_intended_url', $request->all()['url_cid']);
            }
            return redirect(URL::route('login'));
        }else{
            if(!(UsuarioSocio::where('usuario_id', Auth::id())->first())){
                return redirect()->back()->with('warning', 'Debe ser socio para poder acceder a este recurso.');
            }
        }
        return $next($request);
    }
}
