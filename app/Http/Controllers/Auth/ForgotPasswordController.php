<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /*
     * Sobreescribir el funcionamiento normal del request form.
     * Recuperar la página en la que estaba para no saltar a institucional
     * Si no existe la página anterior => setting('site.menu_principal')
     * 
     * @return view
     */
    public function showLinkRequestForm()
    {
        // recuperar la página anterior
        $pagina = \App\Pagina::getPaginaAnterior();
        // si no había página, recuperar el menú principal
        if (!$pagina) {
            $nombre_menu = setting('site.menu_principal');
        }
        if(empty($nombre_menu)){
            $nombre_menu = 'home';
        }
        
        return view('auth.passwords.email', compact('pagina', 'nombre_menu'));
    }

    public function passwordResset(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == 'passwords.sent'
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }
}
