<?php

namespace App\Http\Controllers;

use App\Mail\RegistroUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Usuario;

class UserController extends Controller
{
    public function index(Request $request)
    {

        if (isset($request)) {

            $front_auth = $request->front_token;

            // Get admin user from backend
            $adminUser = DB::table('users')->where('id', $request->admin_id)->first();

            $clave = '3WAYS';

            if (hash_hmac('sha256', $adminUser->front_token, $clave) == $front_auth) {

                if (Auth::check()) {
                    Auth::logout();
                    session_destroy();
                    // Destruir la cookie para VPC-R
                    if (isset($_COOKIE['vpcr_data'])) setcookie('vpcr_data', '', time() - 3600, "/");
                }

                // Get user to login
                $loginUser = User::findOrFail($request->id);

                // Atuhenthicate front user
                Auth::loginUsingId($loginUser->id);

                if (Auth::user()) : // Verificar que el usuario está logueado y guardar el id encriptado
                    if (isset($_COOKIE[config('cookie-consent.cookie_name')])) { //Compruebo que la configuracion de cookies permite guardar la info del usuario
                        if (strpos($_COOKIE[config('cookie-consent.cookie_name')], config('cookie-consent.cookies-internas')) !== false) {
                            setcookie("vpcr_data", $this->encrypt(Auth::user()->id), time() + (86400 * 30), "/");
                        }
                    }
                endif;

                return redirect('/formacion/aula');
            } else {
                // El token no coincide
                return 'Error al autentificar el usuario';
            }
        }
        return 0;
    }

    private function encrypt($data)
    {
        $key = "keyEncryptS3W";
        $method = "AES-256-CBC";
        $secret_iv = 'sepd';
        $data = trim($data);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }

    /**
     * 3Ways - Alex R.
     * Sobrescribir el funcionamiento normal (Illuminate\Foundation\Auth\RegistersUsers)
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        config()->set('captcha.sitekey', env('NOCAPTCHA_SITEKEY_v3'));
        config()->set('captcha.secret', env('NOCAPTCHA_SECRET_v3'));

        // recuperar la página anterior
        $pagina = \App\Pagina::getPaginaAnterior();
        // si no había página, recuperar el menú principal

        if (!$pagina || $request->path() == 'registro-usuario') {
            $nombre_menu = setting('site.menu_principal');
        }
        return view('auth.registroUsuario_container', compact('pagina', 'nombre_menu'));
    }

    /**
     * 3Ways - Alex R.
     * Sobrescribir el funcionamiento normal y registrar solo a usuario
     *  (Illuminate\Foundation\Auth\RegistersUsers)
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        config()->set('captcha.sitekey', env('NOCAPTCHA_SITEKEY_v3'));
        config()->set('captcha.secret', env('NOCAPTCHA_SECRET_v3'));

        // validar los datos de formulario
        $validar = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'nif' => 'required|string|min:6',
            'email' => 'required|email|unique:usuarios,email',
            'password' => [
                'required',
                'string',
                'min:6'
            ],
            'password_confirm' => 'required|same:password',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $usuario = new Usuario;
        $usuario->password = Hash::make($request->input('password'));
        $usuario->email = $request->input('email');
        $usuario->nombre = $request->input('nombre');
        $usuario->apellidos = $request->input('apellidos');
        $usuario->dni = $request->input('nif');
        $usuario->save();

        /**
         * 3 ways - Euro Fuenmayor - Agregado enviar correo cuando se registra un usuario sin sociedad
         */

        Mail::to($usuario->email)
            ->queue(new RegistroUsuario([
                'nombre' => $usuario->nombre_completo,
                'usuario' => $usuario->email,
                'password' => $request->input('password'),
            ]));

        // copia al buzón sepd@
        // Mail::to('carlosanselmi2@gmail.com')
        //     ->queue(new RegistroUsuario([
        //         'nombre' => $usuario->nombre_completo,
        //         'usuario' => $usuario->email,
        //         'password' => '******',
        //     ]));

        // recuperar la página anterior ($request->pagina)
        $pagina = \App\Pagina::getPaginaBySlug($request->input('pagina'));
        // si no había página, recuperar el menú principal
        if (!$pagina || $request->path() == 'registro-usuario') {
            $nombre_menu = setting('site.menu_principal');
        }

        return view('auth.registradoUsuario', compact('pagina', 'nombre_menu'));
    }

    public function show_post_register()
    {
        $pagina = null;

        $nombre_menu = setting('site.menu_principal');

        return view('auth.registradoUsuario', compact('pagina', 'nombre_menu'));
    }
}
