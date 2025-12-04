<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CursoInscripcion;
use App\Mail\CursoInscripcionAdmin;
use App\Curso;
use App\CursoExpediente;
use App\CursosClaveUnica;
use App\UsuarioCurso;
use App\PromocionClave;
use App\Pagina;
use App\UsuarioPago;
use App\UsuarioPagoCurso;
use App\Factura;

class InscripcionesController extends Controller
{
    /* 3 Ways - Alexis Bogado */

    public function __construct()
    {
        if (get_called_class() != 'App\Http\Controllers\CursoPagoController')
            $this->middleware('auth');
    }

    /**
     * Mostrar vista inscripción
     *
     * @return void
     */
    public function mostrar($id)
    {

        $curso = Curso::find($id);

        if ($curso->hay_plazas()) {
            // Se puede inscribir en curso

            $user = Auth::user();

            $curso = Curso::findOrFail($id);

            if ($user->usuario_cursos->where('curso_id', $curso->id)->count() > 0) return redirect()->route('curso.hacer', $curso->id);
            if ($curso->descripcion_estado == 'Pendiente de lanzamiento' || $curso->descripcion_estado == 'Cerrado' || $curso->descripcion_estado == 'Próxima convocatoria' || !$curso->registroAbierto()) {
                $estado_curso = $curso->descripcion_estado;
                $pagina = Pagina::getPaginaBySlug('cursos');
                return view('cursos.inscripcion-error', compact('user', 'curso', 'pagina', 'estado_curso'));
            }

            if (!$curso->configuracion(4) && !$curso->configuracion(6)) :
                if ($curso->configuracion(5)) {
                    if ($user->es_socio() && ($curso->precio_socio <= 0 || is_null($curso->precio_socio))) $this->store($curso);
                } else {
                    if ($user->es_socio() && ($curso->precio_socio <= 0 || is_null($curso->precio_socio))) $this->store($curso);
                    elseif (!$user->es_socio() && ($curso->precio_nosocio <= 0 || is_null($curso->precio_nosocio))) $this->store($curso);
                }
            endif;

            $pagina = Pagina::getPaginaBySlug('cursos');
            
            if($curso->external_url != null){
                return redirect()->route('curso.hacer', $curso->id);
            }else{
                return view('cursos.inscripcion', compact('user', 'curso', 'pagina'));
            }
            
        } else {
            // No se puede inscribir en el curso: está lleno.
            return redirect()->route('curso.mostrar', $id);
        }
    }

    /**
     * Inscripción al curso a través de clave promocional
     *
     * @param int $id Curso id
     * @param Request $request
     * 
     * @return void
     */
    public function accesoClave($id, Request $request)
    {
        $user = Auth::user();

        $curso = Curso::with('cursos_clave_unica', 'promociones')->findOrFail($id);
        if ($user->usuario_cursos->where('curso_id', $curso->id)->count() > 0) return abort(404);
        if ($curso->descripcion_estado == 'Pendiente de lanzamiento' || $curso->descripcion_estado == 'Cerrado' || $curso->descripcion_estado == 'Próxima convocatoria' || !$curso->registroAbierto()) return abort(404);
        if (!$curso->configuracion(4) && !$curso->configuracion(6) || $curso->promociones()->count() <= 0) return redirect()->back()->with('error-message', 'No se ha encontrado ninguna promoción en este curso.');
        $clave = $request->input('clave');
        $unique_predefined_password = '';
        $unique_predefined_password_curso_id = '';

        $promocionIds = array_map(function ($promocion) {
            return $promocion["promocion_id"];
        }, $curso->promociones->toArray());
        if (!empty($curso->cursos_clave_unica[0])) {
            $unique_predefined_password = $curso->cursos_clave_unica[0]->password;
            $unique_predefined_password_curso_id = $id;
        }

        if ($curso->configuracion(4) && ($clave == null || $clave == '')) {
            return redirect()->back()->with('error-message', 'La clave ingresada no es correcta.');
        }
        $promocion_clave = PromocionClave::where('usuario_id', null)->where('clave', $clave)->whereIn('promocion_id', $promocionIds)->first();
        if ($curso->configuracion(4) && empty($promocion_clave)) {
            return redirect()->back()->with('error-message', 'La clave ingresada no es correcta.');
        }
        if ($unique_predefined_password != '' && is_null($promocion_clave) && !($clave == $unique_predefined_password && $id == $unique_predefined_password_curso_id)) {
            // Deshabilitar el registro de clave fallida
            // $this->storeRegistro($curso, $clave, true);
            return redirect()->back()->with('error-message', 'La clave ingresada no es correcta.');
        }

        // Creamos logica de registrar promocion para el usuario si la contraseña es correcta
        if ($unique_predefined_password_curso_id != '' && $unique_predefined_password != '' && $clave != null) {
            $promocionClave = PromocionClave::where('usuario_id', $user->id)->where('promocion_id', $curso->promociones[0]->promocion_id)->first();
            if (!empty($curso->cursos_clave_unica[0])) {
                if (!$promocionClave) {
                    if ($curso->cursos_clave_unica[0]->limit_codes <= 0) {
                        return redirect()->back()->with('error-message', 'Código de promoción agotado.');
                    }

                    $claveUnica = CursosClaveUnica::where('id', $curso->cursos_clave_unica[0]->id)->first();
                    $claveUnica->limit_codes = intval($claveUnica->limit_codes) - 1;
                    $claveUnica->save();

                    $promClave = new PromocionClave();
                    $promClave->promocion_id = $curso->promociones[0]->promocion_id;
                    $promClave->usuario_id = $user->id;
                    $promClave->clave = $unique_predefined_password;
                    $promClave->save();

                    $promocion_clave =  $promClave;
                }
            }
        }

        $precio = ($user->es_socio() ? $curso->precio_socio : $curso->precio_nosocio);
        if ($promocion_clave) {
            if ($promocion_clave->promocion->descuento > 0) // Aplicar descuento asociado a la promoción
                $precio -= (($precio / 100) * $promocion_clave->promocion->descuento);
        }
        if ($precio > 0 && empty($promocion_clave) && !($clave == $unique_predefined_password && $id == $unique_predefined_password_curso_id)) {
            $this->createPago($curso, $precio, $promocion_clave);
            return CursoPagoController::crear_pasarela($curso, $precio);
        }
        if ($precio > 0 && $promocion_clave && !($clave == $unique_predefined_password && $id == $unique_predefined_password_curso_id)) :
            $this->createPago($curso, $precio, $promocion_clave);
            return CursoPagoController::crear_pasarela($curso, $precio);
        else :
            if ($promocion_clave && !($clave == $unique_predefined_password && $id == $unique_predefined_password_curso_id)) {
                $this->updatePromocionClave($promocion_clave, $user->id);
            }
            $this->store($curso, $clave);
        endif;

        return redirect()->route('curso.hacer', $curso->id);
    }

    // Inscribir usuario al curso
    public function store($curso, $clave = null, $send_bill = false, $usuario_pago_id = 0)
    {
        if ($usuario_pago_id <= 0) // Esto sería en caso de que el curso no tenga precio
            $usuario_pago_id = $this->createPago($curso, 0, $clave);

        $usuario_pago = UsuarioPago::find($usuario_pago_id);

        $usuario_curso = new UsuarioCurso;
        $usuario_curso->usuario_id = $usuario_pago->user->id;
        $usuario_curso->curso_id = $curso->id;
        $usuario_curso->curso_expediente_id = $curso->expedientePlazaLibre();
        $usuario_curso->save();

        DiplomasController::comprobar_diploma($usuario_curso);
        $this->updatePago($usuario_pago_id);

        // Enviar email si el formato es curso
        if ($curso->formato != "curso")
            return;
        
        $factura = null;

        if ($send_bill) {
            
            $factura = $this->storeFactura($curso->titulo, ($usuario_pago->user->es_socio() ? $curso->precio_socio : $curso->precio_nosocio), $usuario_pago->user);

            Mail::to(setting('site.mail_facturacion'))->send(new CursoInscripcionAdmin($usuario_pago->user, $curso, $factura));

        } else {
            // Mail::to($usuario_pago->user->email)->queue(new CursoInscripcion($usuario_curso, $factura));
        }

        try {
            Mail::to($usuario_pago->user->email)->queue(new CursoInscripcion($usuario_curso, $factura));
            // ->bcc(setting('site.mail_facturacion'))
        } catch (\Throwable $th) {
            report($th);
        }
    }

    /**
     * Crear usuario pago con estado pendiente
     * 
     * @param \App\Curso $curso
     * @param string $clave
     *
     * @return int
     */
    public function createPago($curso, $precio, $clave = null)
    {
        $user = Auth::user();

        $usuario_pago = new UsuarioPago;
        $usuario_pago->usuario_id = $user->id;
        $usuario_pago->estado_pago_id = 2; // Estado nuevo
        $usuario_pago->metodo_pago_id = (($precio > 0) ? 24 : 20);
        $usuario_pago->precio = $precio;
        $usuario_pago->save();

        $usuario_pago_id = $usuario_pago->id;

        $usuario_pago_curso = new UsuarioPagoCurso;
        $usuario_pago_curso->usuario_pago_id = $usuario_pago_id;
        $usuario_pago_curso->curso_id = $curso->id;
        if (!is_null($clave)) $usuario_pago_curso->clave = $clave;
        $usuario_pago_curso->save();

        return $usuario_pago_id;
    }

    /**
     * Actualizar el pago con el estado final
     *
     * @param int $pago_id
     * @param bool $error
     * 
     * @return void
     */
    public function updatePago($pago_id, $error = false)
    {
        $usuario_pago = UsuarioPago::find($pago_id);
        $usuario_pago->estado_pago_id = ($error ? 7 : (($usuario_pago->precio > 0) ? 4 : 6));
        $usuario_pago->save();
    }

    /**
     * Actualizar la promoción clave con el usuario que la ha usado
     *
     * @param int $user_id
     * @param string $clave
     * 
     * @return void
     */
    public function updatePromocionClave($clave, $user_id)
    {
        $clave->usuario_id = $user_id;
        $clave->save();
    }

    /**
     * Guardar datos para generar factura
     * 
     * @param string $curso_nombre (nombre del curso)
     * @param int $precio (precio que ha pagado por el curso)
     * 
     * @return Factura
     */
    public function storeFactura($curso_nombre, $precio, $user)
    {
        $direccion = null;

        if ($user->direcciones) :
            if ($user->direcciones->via) $direccion .= ("{$user->direcciones->via} ");
            if ($user->direcciones->direccion) $direccion .= ("{$user->direcciones->direccion}.<br />");
            if ($user->direcciones->codigo_postal) $direccion .= ("{$user->direcciones->codigo_postal} - ");
            if ($user->direcciones->ciudad) $direccion .= ("{$user->direcciones->ciudad}, ");
            if (($user->direcciones->pais && $user->direcciones->pais->nombre == "España") && $user->direcciones->provincia) $direccion .= ("{$user->direcciones->provincia->nombre}.<br />");
            elseif ($user->direcciones->provincia_otros) $direccion .= ("{$user->direcciones->provincia_otros}.<br />");
            if ($user->direcciones->pais) $direccion .= ("{$user->direcciones->pais->nombre}.");
        endif;

        $bill_id = 0;
        $last_bill_code = Factura::orderByDesc('created_at')->pluck('numero')->first();
        if (!$last_bill_code) :
            $bill_id = (date('Y') . '0001');
        else :
            $last_bill_year = substr($last_bill_code, 0, 4);

            if ($last_bill_year != date('Y')) :
                $bill_id = (date('Y') . '0001');
            else :
                $last_bill_number = substr($last_bill_code, 4, 4);
                $bill_id = (date('Y') . str_pad(($last_bill_number + 1), 4, '0', STR_PAD_LEFT));
            endif;
        endif;

        $factura = new Factura;
        $factura->nombre_completo = $user->nombre_completo;
        $factura->dni = $user->dni;
        $factura->direccion = $direccion;
        $factura->email = $user->email;
        $factura->concepto = "Inscripción a curso: {$curso_nombre}";
        $factura->precio = $precio;
        $factura->numero = $bill_id;
        $factura->save();

        return $factura;
    }
}
