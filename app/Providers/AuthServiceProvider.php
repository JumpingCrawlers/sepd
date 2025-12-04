<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// Para chequeo de login en la plataforma antigua
// A eliminar en la integración total
use Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /*
        |--------------------------------------------------------------------------
        | SEPD-OV: Login integrado con el sistema antiguo
        | ELIMINAR DESPUES DE LA INTEGRACIÓN TOTAL DEL SISTEMA ANTIGUO
        |--------------------------------------------------------------------------
        |
        | Comprobar si existe una sesión y el usuario no está conectado.
        | Si existe, hay que autenticar al usuario
        |
        */

        // @session_start();
        if (isset($_SESSION['sepd_link_user']) && $_SESSION['sepd_link_user']>0 && !Auth::user()) {
            Auth::loginUsingId($_SESSION['sepd_link_user']);
        }
        
        // Fix login VPC-R || 3 Ways - Alexis Bogado
        if (Auth::user()): // Verificar que el usuario está logueado y guardar el id encriptado
            if(isset($_COOKIE[config('cookie-consent.cookie_name')])){ //Compruebo que la configuracion de cookies permite guardar la info del usuario
                if(strpos($_COOKIE[config('cookie-consent.cookie_name')], config('cookie-consent.cookies-internas')) !== false){
                    setcookie("vpcr_data", $this->encrypt(Auth::user()->id), time() + (86400 * 30), "/");
                }
            }
        endif;

        /*
        |--------------------------------------------------------------------------
        | SEPD-OV: FIN Login integrado con el sistema antiguo
        |--------------------------------------------------------------------------
        |
        */

        //
    }

    /**
     * Función para encriptar
     * 
     * @param string $data
     * 
     * @return string
     */
    private function encrypt($data) {
        $key = "keyEncryptS3W";
        $method = "AES-256-CBC";
        $secret_iv = 'sepd';
        $data = trim($data);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }
}
