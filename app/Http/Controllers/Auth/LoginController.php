<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UsuarioSocio;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/* 
 * Necesario para sobreescribir el LOGIN:
 * Request
 * User
 * Hash
 * Auth
 */
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
// para la exception de permisos insuficientes
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Sobrescribir el formulario de login.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        // no hay página, menú principal
        $pagina = null;
        $miga_pan = '> Conexión de usuarios';
        $nombre_menu = setting('site.menu_principal');
        $route = (isset($_GET["route"]) ? $_GET["route"] : null);

        return view('auth.login', compact('pagina', 'miga_pan', 'nombre_menu', 'route'));
    }

    /**
     * Sobreescribir el proceso de LOGIN
     * Hay que controlar el viejo sistema: email y pass
     * Comprobar que es correcto y guardarlo en el nuevo sistema: email y password
     * 
     * Origen Procedimiento \Illuminate\Foundation\Auth\AuthenticatesUsers.php
     */
    public function login(Request $request)
    {
        // procedimiento standard
        $this->validateLogin($request);

        // procedimiento standard
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        /*
         * Procedimiento real de login. Hay que controlar los perfiles de usuario, según el código antiguo.
         * En caso de éxito:
         * se añade la parte del sistema antiguo para logear a "usuario"
         * se añade el "else { ... }" para comprobar la contraseña antigua
         */
        // $permisos_portal = array('SOC', 'SUP', 'APS', 'NSC');
        $permisos_insuficientes = false;
        if(!(User::checkEliminado($request->email))){
            $request->session()->invalidate();
            return $this->sendFailedLoginResponse($request, false);
        }
        if ($this->attemptLogin($request)) {
            // login correcto -> realizar login en el sistema antiguo
             $usuario = Auth::user();
            // comprobar el tipo de perfil (según acceso antiguo)
            // foreach ($usuario->perfiles as $perfil) {
            //     if (in_array($perfil->id_perfil, $permisos_portal)) {
                    $usuario->crearSesionAntigua();
                    // procedimiento standard
                   // return json_encode(User::checkEliminado($request->email));
                if (User::checkEliminado($request->email)){ // Verificar que el usuario no esté eliminado
                    return $this->sendLoginResponse($request);
                 }
            // }
            // Si ha llegado hasta aquí es que no tiene ninguno de los permisos permitidos
            // Hay que desloguear (según \Illuminate\Foundation\Auth\AuthenticatesUsers.php)
            $this->guard()->logout();
            $request->session()->invalidate();
            // TODO: $permisos_insuficientes = true;
        }
        else {
            // comprobar si la conexión antigua funciona (con email y pass)
            if ($usuario = User::checkLoginViejo($request->email, $request->password)) {
                // si es correcta, guardar el password codificado para el usuario (password)
                $usuario->password = Hash::make($request->password);
                $usuario->save();
                // comprobar el tipo de perfil (según acceso antiguo)
                // foreach ($usuario->perfiles as $perfil) {
                //     if (in_array($perfil->id_perfil, $permisos_portal)) {
                //         // realizar de nuevo el attemptLogin
                        // if ($this->attemptLogin($request)) {
                            // realizar el login en el sistema antiguo
                            // $usuario->crearSesionAntigua();
                            // volver autenticado
                            return $this->sendLoginResponse($request);
                        // }
                //     }
                // }
                // // si llega hasta aquí, es que no tiene permisos
                $permisos_insuficientes = true;
            }
        }

        // procedimiento standard
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request, $permisos_insuficientes);
    }

    /**
     * En caso de autentificación correcta, redireccionar a donde se iba :)
     * 
     * @param Request $request
     * @param type $user
     * @return type
     */
    protected function authenticated(Request $request, $user)
    {
        if(session()->has('cid_servicios_intended_url') && UsuarioSocio::where('usuario_id', Auth::id())->first()){
            $url = url(session()->get('cid_servicios_intended_url'));
            session()->forget('cid_servicios_intended_url');
            return redirect()->intended($url);
        }
        if(session()->has('cid_servicios_intended_url') && !(UsuarioSocio::where('usuario_id', Auth::id())->first())){
            session()->forget('cid_servicios_intended_url');
            session()->flash('warning', 'Debe ser socio para poder acceder a este recurso.');
            return redirect('/inicio');
        }
        if ($request->get("route")){
            return redirect($request->get("route"));
        }
        return redirect()->intended(url()->previous());
    }

    /*
    |--------------------------------------------------------------------------
    | SEPD-OV: Login integrado con el sistema antiguo
    | ELIMINAR LA FUNCIÓN DESPUES DE LA INTEGRACIÓN TOTAL DEL SISTEMA ANTIGUO
    |--------------------------------------------------------------------------
    |
    | Logout eliminando la sesión del sistema antiguo
    |
    */

    /**
     * Sobrescribir el funcionamiento normal
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // destruir la sesión PHP para el sistema antiguo
        @session_destroy();

        // y ejecutar el procedimiento normal de Laravel (AuthenticatesUsers::logout()
        $this->guard()->logout();
        $request->session()->invalidate();
        
        // Destruir la cookie para VPC-R
        if (isset($_COOKIE['vpcr_data'])) setcookie('vpcr_data', '', time() - 3600, "/");

        return redirect('/');

    }
    /*
    |--------------------------------------------------------------------------
    | SEPD-OV: FIN Login integrado con el sistema antiguo
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Sobrescribir la devolución del error -> mensaje de permisos insuficientes
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool permisos_insuficientes // si true, ha fallado por permisos de perfil
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request, $permisos_insuficientes = false)
    {
        ($permisos_insuficientes) ? $mensaje = "Este usuario no tiene permisos suficientes. Debe conectar desde el Aula Virtual." : $mensaje = trans('auth.failed');
        throw ValidationException::withMessages([
            $this->username() => [$mensaje],
        ]);
    }



}
