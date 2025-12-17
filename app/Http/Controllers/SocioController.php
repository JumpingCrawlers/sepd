<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Mail\SolicitudSocio;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\RegistroUsuario;
use App\Pagina;
use App\SocioTipo;
use App\Socio;
use App\SocioSolicitud;
use App\SocioCodigo;
use App\User;
use App\UsuarioDatoBasico;
use App\UsuarioDatoProfesional;
use App\UsuarioDireccion;
use App\UsuarioCentro;
use App\UsuarioTarjeta;
use App\UsuarioDatoBancario;
use App\UsuarioEspecialidad;
use App\Usuario;
use App\UsuarioSocio;
use Freelancehunt\Validators\CreditCard;
use Illuminate\Support\Str;

class SocioController extends Controller
{
    /* 3 Ways - Alexis Bogado */

    /**
     * Esqueleto de la página para hazte_socio
     */
    protected $pagina;

    /**
     * Recuperar la página en la construcción
     * 
     * @return void
     */
    public function __construct()
    {
        $pagina = Pagina::getPaginaBySlug('hazte_socio');
        $this->pagina = $pagina;
    }

    /***************************************************
     *                                                 *
     *         Funciones protected de gestión          *
     *                                                 *
     ***************************************************/

    /**
     * Validador de contenido del formulario: cada paso tiene su validación
     * 
     * @param int $paso Paso a validar
     * @param array $data Array de datos a validar
     * @param string $tipo_socio Tipo de solicitud de socio
     * 
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator($paso, array $data, $tipo_socio = null)
    {
        $valida = [];

        switch ($paso):
            case 2:
                /* DATOS PERSONALES */

                if (Auth::guest()) : // Validar datos para solicitudes sin usuario logueado
                    $valida['tratamiento'] = "required|string|max:20";
                    $valida['nombre'] = "required|string|max:50";
                    $valida['apellidos'] = "required|string|max:100";
                    $valida['correo'] = "required|email";
                    $valida['nif'] = "required";
                endif;

                $valida['via'] = "required|string";
                $valida['direccion'] = "required|string";
                $valida['localidad'] = "required|string";
                $valida['cp'] = "required|string";

                if ($tipo_socio != "internacional") $valida['provincia'] = "required";
                if ($tipo_socio == "internacional" || $data['provincia'] == '000') $valida['provincia_otros'] = "required|string|nullable";

                $valida['pais'] = "required|integer";

                if ($tipo_socio == 'internacional') $valida['telefono'] = "required|string";
                else $valida['telefono_movil'] = "required|string";

                // Calcular la fecha mínima edad (hace 20 años?)
                $str_fecha_limite = date_format(date_sub(date_create(), date_interval_create_from_date_string('20 years')), 'Y-m-d H:i:s');
                $valida['nacimiento'] = "required|date|before:{$str_fecha_limite}";
                $valida['sexo'] = 'required';

                /* END DATOS PERSONALES */

                /* DATOS PROFESIONALES */

                $valida['titulacion'] = "required|string";
                // $valida['especialidad'] = "required";
                $valida['centro'] = "required|string";

                if ($tipo_socio == 'formacion') : // Datos profesionales para tipo 'Formación'
                    $valida['fecha_inicio_mir'] = ["required", "regex:/^[0-9]{4}/"];
                    $valida['fecha_fin_mir'] = ["required", "regex:/^[0-9]{4}/"];
                    $valida['nombre_tutor'] = "required|string";
                    $valida['telefono_tutor'] = "required|string";
                    $valida['email_tutor'] = "required|email";
                else :
                    // Pago mediante domiciliación bancaria
                    $valida['titular_cuenta'] = "required_if:modo_pago,domiciliacion|string|nullable";
                    $valida['banco'] = "required_if:modo_pago,domiciliacion|string|nullable";
                    $valida['iban'] = ["required_if:modo_pago,domiciliacion", "regex:/^[A-Za-z]{2}[0-9]{2}/", "nullable"];
                    $valida['iban_entidad'] = ["required_if:modo_pago,domiciliacion", "regex:/^[0-9]{4}/", "nullable"];
                    $valida['iban_entidad'] = ["required_if:modo_pago,domiciliacion", "regex:/^[0-9]{4}/", "nullable"];
                    $valida['iban_oficina'] = ["required_if:modo_pago,domiciliacion", "regex:/^[0-9]{4}/", "nullable"];
                    $valida['iban_dc'] = ["required_if:modo_pago,domiciliacion", "regex:/^[0-9]{4}/", "nullable"];
                    $valida['iban_cuenta'] = ["required_if:modo_pago,domiciliacion", "regex:/^[0-9]{4}/", "nullable"];
                    $valida['iban_cuenta2'] = ["required_if:modo_pago,domiciliacion", "regex:/^[0-9]{4}/", "nullable"];

                    // Pago mediante tarjeta de crédito
                    $valida['titular_tarjeta'] = "required_if:modo_pago,tarjeta|string|nullable";
                    $valida['tipo_tarjeta'] = "required_if:modo_pago,tarjeta";
                    $valida['numero_tarjeta_1'] = ["required_if:modo_pago,tarjeta|required_if:tipo_tarjeta,VISA|required_if:tipo_tarjeta,MasterCard", "regex:/^[0-9]{4}/", "nullable"];
                    $valida['numero_tarjeta_2'] = ["required_if:modo_pago,tarjeta|required_if:tipo_tarjeta,VISA|required_if:tipo_tarjeta,MasterCard", "regex:/^[0-9]{4}/", "nullable"];
                    $valida['numero_tarjeta_3'] = ["required_if:modo_pago,tarjeta|required_if:tipo_tarjeta,VISA|required_if:tipo_tarjeta,MasterCard", "regex:/^[0-9]{4}/", "nullable"];
                    $valida['numero_tarjeta_4'] = ["required_if:modo_pago,tarjeta|required_if:tipo_tarjeta,VISA|required_if:tipo_tarjeta,MasterCard", "regex:/^[0-9]{4}/", "nullable"];
                    $valida['numero_tarjeta_amex'] = ["required_if:modo_pago,tarjeta|required_if:tipo_tarjeta,'American Express'", "regex:/^[0-9]{15}/", "nullable"];
                endif;

                /* END DATOS PROFESIONALES */

                // Mensajes personalizados
                $messages =  [
                    'nacimiento.before' => 'La fecha de nacimiento indica una edad menor de 20 años',
                    'especialidad.required' => 'Debes introducir o seleccionar alguna especialidad principal',
                    'numero_tarjeta_amex.*' => 'El formato debe ser 15 dígitos sin espacios'
                ];
                break;

            case 1:
                $valida['tipo_socio'] = ["required", "regex:(numerario|formacion|internacional|graduado_enfermeria)"];
                $messages = [];
                break;
        endswitch;

        return Validator::make($data, $valida, $messages);
    }

    /***************************************************
     *                                                 *
     *           Métodos accesibles por ruta           *
     *                                                 *
     ***************************************************/

    /**
     * Mostrar selección de tipo de socio
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function showTipoSocioForm()
    {
        $cuota_numerario = SocioTipo::getSociedadCuotaByName('Miembro Numerario SEPD');
        $cuota_internacional = SocioTipo::getSociedadCuotaByName('Miembro Internacional');
        $cuota_graduado_enfermeria = SocioTipo::getSociedadCuotaByName('Miembro graduado en enfermeria');

        if (Auth::check()) {
            $es_socio = UsuarioSocio::where('usuario_id', Auth::user()->id)->first();
            if ($es_socio) {
                if ($es_socio->deleted_at == NULL) {
                    $es_socio = 'vigente';
                } else {
                    $es_socio = 'baja';
                }
            } else {
                $es_socio = 'no';
            }
        } else {
            $es_socio = 'no';
        }
        return view('socios.tipo_socio', [
            'pagina' => $this->pagina,
            'cuota_numerario' => $cuota_numerario,
            'cuota_internacional' => $cuota_internacional,
            'cuota_graduado_enfermeria' => $cuota_graduado_enfermeria,
            'es_socio' => $es_socio
        ]);
    }

    /**
     * Validar tipo de socio y mostrar formulario de petición
     * 
     * @param Request $request Datos del formulario
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    /* validar tipo de socio y mostrar formulario de petición */
    public function saveTipoSocio(Request $request)
    {
        $validator = $this->validator(1, $request->all());
        $validator->validate();

        $request->session()->put('tipo_socio', $request->tipo_socio); // guardar el tipo de socio en la sesión
        return redirect('/hazte_socio/registro');
    }

    /**
     * Mostrar formulario de solicitud según el tipo de socio
     * 
     * @param Request $request Datos del formulario
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        $tipo_socio = $request->session()->get('tipo_socio'); // recuperar el tipo de socio en la sesión
        $sociedades = (($tipo_socio == "internacional") ? SocioTipo::getSociedadesInternacionales() : null); // para los internacionales hay que recuperar las sociedades (socios_tipos)

        return view('socios.registro', [
            'pagina' => $this->pagina,
            'tipo_socio' => $tipo_socio,
            'sociedades' => $sociedades
        ]);
    }

    /**
     * Validar formulario de petición y mostrar lo que sea que venga después
     * 
     * @param Request $request Datos del formulario
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function validarDatosRegistro(Request $request)
    {
        $validator = $this->validator(2, $request->all(), $request->session()->get('tipo_socio'));
        $validator->validate();

        if ($request->modo_pago == "tarjeta") : // Verificar tarjeta de crédito
            $validator->after(function ($validator) use ($request) {
                // Obtener número y tipo de tarjeta
                if ($request->tipo_tarjeta == "American Express") :
                    $card_number = $request->numero_tarjeta_amex;
                    $card_type = "amex";
                else :
                    $card_number = ($request->numero_tarjeta_1 . $request->numero_tarjeta_2 . $request->numero_tarjeta_3 . $request->numero_tarjeta_4);
                    $card_type = (($request->tipo_tarjeta == "VISA") ? "visa" : "mastercard");
                endif;

                $card = CreditCard::validCreditCard($card_number);
                if (!$card["valid"] || $card["type"] != $card_type) :
                    if ($request->tipo_tarjeta == "American Express") :
                        $validator->errors()->add('numero_tarjeta_amex', 'El número de tarjeta no es válido.');
                    else :
                        $validator->errors()->add('numero_tarjeta_1', 'El número de tarjeta no es válido.');
                        $validator->errors()->add('numero_tarjeta_2', 'El número de tarjeta no es válido.');
                        $validator->errors()->add('numero_tarjeta_3', 'El número de tarjeta no es válido.');
                        $validator->errors()->add('numero_tarjeta_4', 'El número de tarjeta no es válido.');
                    endif;
                endif;

                // Validar fecha de caducidad
                $card_expiration = CreditCard::validDate($request->cad_anno, $request->cad_mes);
                if (!$card_expiration) :
                    $validator->errors()->add('cad_anno', 'La fecha de caducidad no es válida.');
                    $validator->errors()->add('cad_mes', 'La fecha de caducidad no es válida.');
                endif;
            })->validate();
        endif;

        // Comprobar que no exista un usuario con el mismo email o el mismo DNI si la solicitud se está realizando sin ser usuario
        if (Auth::guest() && User::whereNull('deleted_at')->where('email', $request->correo)->orWhere('dni', 'like', "{$request->nif}%")->count() > 0) :
            $validator->after(function ($validator) use ($request) {
                $validator->errors()->add('correo', 'Ya existe una afiliación con alguno de estos datos.');
                $validator->errors()->add('nif', 'Ya existe una afiliación con alguno de estos datos.');
                /**
                 * 3 ways Euro Fuenmayor - Mejorado mensaje que avisa que usuario ya esta registrado con el correo y/o dni en Hazte socio sin estar logeado
                 **/
                return redirect()->back()->with([
                    'usuarioRegistrado' => ['email' => $request->correo, 'dni' => $request->nif]
                ]);
            })->validate();
        endif;

        // Comprobar que no exista ninguna solicitud si la solicitud se está realizando logueado
        if (Auth::check() && SocioSolicitud::whereNull('deleted_at')->where('usuario_id', Auth::user()->id)->count() > 0) :
            $validator->after(function ($validator) {
                $validator->errors()->add('correo', 'Ya existe una solicitud con alguno de estos datos.');
                $validator->errors()->add('nif', 'Ya existe una solicitud con alguno de estos datos.');
                return redirect()->back()->with([
                    'solicitudPendiente' => ['email' => Auth::user()->email, 'dni' => Auth::user()->dni]
                ]);
            })->validate();
        endif;

        // Comprobar código promocional
        $codigo = SocioCodigo::isValid($request->socio_codigo);
        if (!empty($request->socio_codigo) && !$codigo) :
            $validator->after(function ($validator) {
                $validator->errors()->add('socio_codigo', 'Este código promocional no es válido.');
            })->validate();
        endif;

        $tipo_socio = $request->session()->get('tipo_socio');

        // Si está realizando la solicitud sin usuario, se le crea uno sin asignarle contraseña
        $user = (Auth::user() ?? new Usuario);
        if (Auth::guest()) :
            $user->tratamiento = $request->tratamiento;
            $user->nombre = $request->nombre;
            $user->apellidos = $request->apellidos;
            $user->dni = $request->nif;
            $user->email = $request->correo;
            $user->password = Hash::make(Str::random(8));
            $user->save();
        endif;

        // Insertar usuarios_datosbasicos
        $usuario_datobasico = ($user->datos_basicos ?? new UsuarioDatoBasico);
        if (!$user->datos_basicos) $usuario_datobasico->usuario_id = $user->id;
        if ($request->telefono) $usuario_datobasico->telefono = $request->telefono;
        $usuario_datobasico->movil = $request->telefono_movil;
        $usuario_datobasico->sexo = $request->sexo;
        $usuario_datobasico->fecha_nacimiento = $request->nacimiento;

        $usuario_datobasico->save();


        // Insertar usuarios_datosprofesionales
        $usuario_datoprofesional = ($user->datos_profesionales ?? new UsuarioDatoProfesional);
        if (!$user->datos_profesionales) $usuario_datoprofesional->usuario_id = $user->id;
        $usuario_datoprofesional->titulacion = $request->titulacion;

        if ($tipo_socio == "formacion") :
            $usuario_datoprofesional->residencia = $request->centro;
            $usuario_datoprofesional->fecha_inicio_MIR = $request->fecha_inicio_mir;
            $usuario_datoprofesional->fecha_fin_MIR = $request->fecha_fin_mir;
            $usuario_datoprofesional->nombre_tutor = $request->nombre_tutor;
            $usuario_datoprofesional->telefono_tutor = $request->telefono_tutor;
            $usuario_datoprofesional->email_tutor = $request->email_tutor;
        endif;

        $usuario_datoprofesional->save();

        // Insertar usuarios_direcciones
        $usuarios_direcciones = ($user->direcciones ?? new UsuarioDireccion);
        if (!$user->direcciones) $usuarios_direcciones->usuario_id = $user->id;
        $usuarios_direcciones->via = $request->via;

        if ($request->pais == "724") :
            if ($request->provincia == "000") :
                $usuarios_direcciones->provincia_id = 1;
            else :
                $explode_provincia = explode("_", $request->provincia);
                $usuarios_direcciones->provincia_id = (int) $explode_provincia[0];
            endif;

            $usuarios_direcciones->provincia_otros = null;
        else :
            if ($request->provincia == "000") :
                $usuarios_direcciones->provincia_otros = $request->provincia_otros;
            else :
                $usuarios_direcciones->provincia_otros = "Provincia desconocida";
            endif;

            $usuarios_direcciones->provincia_id = null;
        endif;

        $usuarios_direcciones->direccion = $request->direccion;
        $usuarios_direcciones->ciudad = $request->localidad;
        $usuarios_direcciones->pais_id = $request->pais;
        $usuarios_direcciones->codigo_postal = $request->cp;

        $usuarios_direcciones->save();

        // Insertar usuarios_centros
        $usuarios_centros = ($user->centros ?? new UsuarioCentro);
        if (!$user->centros) $usuarios_centros->usuario_id = $user->id;
        if ($request->publico) $usuarios_centros->publico = $request->publico;
        $usuarios_centros->centro = $request->centro;
        if ($request->cargo) $usuarios_centros->cargo = $request->cargo;

        $usuarios_centros->save();

        // Insertar usuarios_tarjetas o usuarios_datosbancarios
        if ($tipo_socio != "formacion") :
            if ($request->modo_pago == "tarjeta") :
                $usuario_tarjeta = ($user->tarjetas ?? new UsuarioTarjeta);
                if (!$user->tarjetas) $usuario_tarjeta->usuario_id = $user->id;
                $usuario_tarjeta->titular = $request->titular_tarjeta;
                $usuario_tarjeta->numero_tarjeta = Crypt::encryptString($request->numero_tarjeta_1 . $request->numero_tarjeta_2 . $request->numero_tarjeta_3 . $request->numero_tarjeta_4);
                $usuario_tarjeta->mes_caducidad = Crypt::encryptString($request->cad_mes);
                $usuario_tarjeta->anno_caducidad = Crypt::encryptString($request->cad_anno);
                $usuario_tarjeta->tipo_tarjeta = $request->tipo_tarjeta;

                $usuario_tarjeta->save();
            else :
                $usuario_datobancario = ($user->datos_bancarios ?? new UsuarioDatoBancario);
                if (!$user->datos_bancarios) $usuario_datobancario->usuario_id = $user->id;
                $usuario_datobancario->nombre = $request->banco;
                $usuario_datobancario->datos_cuenta = Crypt::encryptString($request->iban . $request->iban_entidad . $request->iban_oficina . $request->iban_dc . $request->iban_cuenta . $request->iban_cuenta2);
                $usuario_datobancario->titular = $request->titular_cuenta;

                $usuario_datobancario->save();
            endif;
        endif;

        // Insertar usuarios_especialidades
        $especialidades = ($request->especialidad ?? null);

        if ($especialidades && is_array($especialidades)) : // Revisar
            if (Auth::guest()) :
                foreach ($especialidades as $especialidad_id) :
                    $usuario_especialidad = new UsuarioEspecialidad;
                    $usuario_especialidad->usuario_id = $user->id;
                    $usuario_especialidad->especialidad_id = $especialidad_id;

                    $usuario_especialidad->save();
                endforeach;
            else :
                $usuarios_especialidades_ids = array_map(function ($usuario_especialidad) {
                    return (string) $usuario_especialidad["especialidad_id"];
                }, $user->especialidades->toArray());

                foreach ($usuarios_especialidades_ids as $usuario_especialidad_id) :
                    if (in_array($usuario_especialidad_id, $especialidades)) continue;

                    $usuario_especialidad = UsuarioEspecialidad::where(['usuario_id' => $user->id], ['especialidad_id' => $usuario_especialidad_id])->with('especialidad')->first();
                    if (isset($usuario_especialidad) && isset($usuario_especialidad->especialidad->habilitado)) {
                        if ($usuario_especialidad && $usuario_especialidad->especialidad->habilitado == 1) $usuario_especialidad->delete();
                    }
                endforeach;

                // Insertar nuevas especialidades que no tenía
                $usuarios_especialidades = [];

                foreach ($especialidades as $especialidad) :
                    if ($user->hasEspecialidad($especialidad)) continue;

                    $usuarios_especialidades[] = new UsuarioEspecialidad([
                        'usuario_id' => $user->id,
                        'especialidad_id' => $especialidad
                    ]);
                endforeach;

                $user->especialidades()->saveMany($usuarios_especialidades);
            endif;
        endif;

        // Insertar socio solicitud
        $socio_solicitud = new SocioSolicitud;
        $socio_solicitud->usuario_id = $user->id;


        if ($tipo_socio == "internacional" && $request->sociedad != 4)
            $socio_solicitud->socio_tipo_id = (int) $request->sociedad;
        else if ($tipo_socio == "graduado_enfermeria")
            $socio_solicitud->socio_tipo_id = 31; // socio enfermeria, se colocó manualmente para solventar el error
        else
            $socio_solicitud->socio_tipo_id = ((int) SocioTipo::where('acortado', $tipo_socio)->pluck('id')->first() ?? 1);

        $socio_solicitud->socio_solicitud_estado_id = 2; // Solicitud estado: pendiente

        if ($codigo) : // Si se ha registrado con un código válido
            $socio_solicitud->mensaje = "Con clave {$codigo}";
            $socio_solicitud->codigo = $codigo;
        endif;

        //Compruebo si ya habia sido socio

        $usuario_socio = UsuarioSocio::where('usuario_id', $user->id)->first();
        if ($usuario_socio && $usuario_socio->deleted_at != NULL) :
            $socio_solicitud->mensaje = "Ya había sido socio. Pide alta de nuevo.";
        endif;

        $socio_solicitud->usuario_nuevo = (Auth::guest() ? 1 : 0);

        $socio_solicitud->save();

        // Enviar datos de la solicitud por email con copia al admin
        Mail::to($socio_solicitud->usuario->email)
            ->queue(
                new SolicitudSocio([
                    'nombre' => $socio_solicitud->usuario->nombre_completo,
                    'tipo' => strtoupper($tipo_socio),
                    'uid' => $socio_solicitud->id,
                ])
            );
        /**
         * 3 ways Euro Fuenmayor - Mejorado asunto de copia al correo configurado con el setting de email.socios, ahora inclue apellidos e ID del usuario receptor del correo
         **/
        Mail::to(explode(",", setting('site.mail_socios')))
            ->queue(
                new SolicitudSocio([
                    'nombre' => $socio_solicitud->usuario->nombre_completo,
                    'tipo' => strtoupper($tipo_socio),
                    'uid' => $socio_solicitud->id,
                    'subject' => "Solicitud Socio: {$socio_solicitud->usuario->apellidos} (ID: {$socio_solicitud->usuario->id})"
                ])
            );

        // Mandar a la vista de confirmación
        return view('socios.confirmacion', [
            'pagina' => $this->pagina,
            'tpv' => ($request->modo_pago == "tarjeta" && (!$codigo || SocioCodigo::requirePayment($codigo))),
            'uid' => $socio_solicitud->id
        ]);
    }
}
