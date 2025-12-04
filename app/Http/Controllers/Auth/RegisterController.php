<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Mail\SolicitudDatosAdmin;
use App\Mail\SolicitudDatos;
use Illuminate\Support\Facades\Mail;
use App\Pagina;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre_completo' => 'required|string|max:255',
            'nif' => 'required|string|min:6',
            'es_socio' => 'required',
            'mail' => 'required|email',
            'telefono' => 'required|integer|min:6',
            'observaciones' => 'max:255'
        ]);
    }

    /**
     * Sobrescribir el funcionamiento normal (Illuminate\Foundation\Auth\RegistersUsers)
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $pagina = Pagina::getPaginaAnterior();
        if (!$pagina) $nombre_menu = setting('site.menu_principal');

        return view('auth.register', [
            'pagina' => $pagina,
            'nombre_menu' => $nombre_menu ?? null,
            'modal_title' => 'Formulario de contacto'
        ]);
    }

    /**
     * Sobrescribir el funcionamiento normal (Illuminate\Foundation\Auth\RegistersUsers)
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        $validator->validate();

        // Check captcha || 3 Ways - Alexis Bogado
        $captcha = $request->input('g-recaptcha-response');
        if (!$captcha)
            goto invalidCaptcha;

        $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LevFvYUAAAAADaaU8SrcZlSsARyQxT_h1LUS2cy&response={$captcha}&remoteip={$_SERVER['REMOTE_ADDR']}"), true);

        if (!$response['success'])
            goto invalidCaptcha;

        $userFind = User::where('email', $request->mail)->value('id');
        if (empty($userFind) || $userFind == null)
            return back()->withErrors(['mail' => 'Usuario no encontrado.']);

        Mail::to(explode(",", setting('site.mail_socios')))->send(new SolicitudDatosAdmin([
            'nombre_completo' => $request->nombre_completo,
            'nif' => $request->nif,
            'es_socio' => $request->es_socio,
            'mail' => $request->mail,
            'telefono' => $request->telefono,
            'observaciones' => $request->observaciones
        ]));

        Mail::to($request->mail)->send(new SolicitudDatos($request->nombre_completo));

        return redirect()->back()->with('success', true);

        invalidCaptcha:
            return back()->withErrors(['captcha' => 'Captcha no válido.']);
    }
}
