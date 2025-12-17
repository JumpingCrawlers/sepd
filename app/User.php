<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
// para la consulta directa de socio
use DB;
use Contacto;
use Auth; // recuperar los datos del usuario conectado
// añadido para generar el password
use Illuminate\Support\Facades\Hash;
// para el formateo de fechas en español
use Illuminate\Support\Facades\Log;
use Jenssegers\Date\Date;
// Trait para guardar los datos de provincia
use App\Traits\Provincia;
// para el envío del password Reset
use App\Notifications\ResetPassword as ResetPasswordNotification;


// Aunque se instala Voyager para acceder a funcionalidades,
//      la autenticación en el frontend se hace con el modelo normal de Laravel.
// class User extends \TCG\Voyager\Models\User
class User extends Authenticatable
{
    use Notifiable;
    use Provincia;

    private const HASH_METHOD = 'aes-128-ecb';
    private const HASH_KEY = 'vzW+V<@9}{8R-fYC';

    /**
     *
     * @variables para sobreescribir comportamiento estándar
     */
    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'apellidos', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function usuario_cursos()
    {
        return $this->hasMany(UsuarioCurso::class, 'usuario_id');
    }

    /* 3 Ways - Alexis Bogado */
    public function getNombreCompletoAttribute()
    {
        return "{$this->tratamiento} {$this->nombre} {$this->apellidos}";
    }

    // Obtener los diplomas del usuario
    public function usuario_diplomas()
    {
        return $this->hasMany(UsuarioDiploma::class, 'usuario_id')->groupBy(['id', 'curso_id', 'bloque_id', 'created_at'])->orderByDesc('created_at')->orderBy('curso_id')->orderBy('bloque_id');
    }

    // Obtener los diplomas del usuario
    public function usuario_diplomas_obtiene_diploma()
    {
        $diplomas = $this->usuario_diplomas;

        $diplomas_obtenidos = collect([]);
        $valDuplicados = [];
        foreach ($diplomas as $key => $usuario_diploma) {
            $usuario_curso = UsuarioCurso::where([['usuario_id', $usuario_diploma->usuario_id], ['curso_id', $usuario_diploma->curso_id]])->first();
            
            if (is_null($usuario_diploma->curso->diploma_id))
                continue;
        
            // COMENTAR O REMOVER ESTE CONDICIONAL CON CONTINUE
            if (is_null($usuario_diploma->curso->fecha_fin_real) || $usuario_diploma->curso->fecha_fin_real > now())
                continue;

            if ($usuario_diploma->curso->external_curso_id) {
                // cursos_external
                if ($usuario_curso && $usuario_curso->obtiene_diploma($this)) {
                    if (in_array($usuario_diploma->curso_id, $valDuplicados) === false) {
                        $diplomas_obtenidos->push($usuario_diploma);
                        array_push($valDuplicados, $usuario_diploma->curso_id);
                    }
                }
            } else if ($usuario_diploma->curso->encuesta_id > 0) {
                // if ($this->encuesta($usuario_diploma->curso)->count() > 0) {
                    if ($usuario_curso->obtiene_diploma($this)) {
                        if (in_array($usuario_diploma->curso_id, $valDuplicados) === false) {
                            $diplomas_obtenidos->push($usuario_diploma);
                            array_push($valDuplicados, $usuario_diploma->curso_id);
                        }
                    }
                // }
            }
        }

        return $diplomas_obtenidos;
    }

    // Obtener solo los diplomas del usuario que tengan créditos
    public function usuario_diplomas_con_creditos()
    {
        return $this->hasMany(UsuarioDiploma::class, 'usuario_id')
            ->join('cursos', 'cursos.id', '=', 'usuarios_diplomas.curso_id')
            ->where('cursos.creditos', '>', '0')
            ->groupBy(['usuarios_diplomas.id', 'usuarios_diplomas.curso_id', 'usuarios_diplomas.bloque_id', 'usuarios_diplomas.created_at'])
            ->orderByDesc('usuarios_diplomas.created_at')
            ->orderBy('usuarios_diplomas.curso_id')
            ->orderBy('usuarios_diplomas.bloque_id');
    }

    // Obtener datos de socio
    public function es_socio()
    {
        $socio = UsuarioSocio::where('usuario_id', $this->id)->first();
        return $socio ? true : false;
    }

    // Comprueba si el socio está dado de alta
    public function es_socio_activo()
    {
        $socio = UsuarioSocio::where('usuario_id', $this->id)->whereNull('deleted_at')->first();
        return $socio ? true : false;
    }
    

    /**
     * 3ways Euro Fuenmayor
     * Método que permite obtener dos posibles respuesta:
     * - Conteo de ítems completado
     * - Boolean, true si el número de completado es igual o mayor que la cantidad de los items del bloque, caso contrario false
     * @param $bloque
     * @param bool $count
     * @return bool|int
     */
    public function items_bloque_completado($bloque, $count = true)
    {
        $items_Ids = array_map(function ($item) {
            return $item["id"];
        }, $bloque->items->toArray());
        $usuario_items = UsuarioItem::where('usuario_id', $this->id)->whereIn('item_id', $items_Ids);
        $items_completed_count = 0;
        foreach ($usuario_items->get() as $usuario_item) {
            $is_item_completed = self::is_item_time_completed($usuario_item);
            if ($is_item_completed) {
                $items_completed_count++;
            }
        }
        return $count ? $items_completed_count : ($items_completed_count >= $bloque->items()->count());
    }

    /**
     * Verificar si ha completado un bloque
     * 
     * @param CursoBloque $bloque
     * 
     * @return bool
     */
    public function bloque_completado($bloque)
    {
        /**
         *  3ways Euro Fuenmayor - Ajuste de criterio para verificar que un usuario ha completado un bloque de curso
         **  Nuevo criterio: si el curso es estricto, se verifica que cada item esta completado, por cada uno completado se incrementa un contador
         *   si el contador es mayor o igual a la cantidad de items del bloque, se considera que el bloque esta completado.
         *   Si el curso no es estricto -cualquier otro tipo- el bloque se considera completado si las cuestionarios han sido aprobados.
         **/
        $curso = Curso::find($bloque->curso_id);
        $items_completed_count = $this->items_bloque_completado($bloque);
        $items_bloque_count = $bloque->items()->count();
        if ($bloque->cuestionarios()->count() > 0) :
            $autoevaluaciones_aprobadas = [];

            $bloque_cuestionarios = $bloque->cuestionarios->where('id_superior', '<=', '0')->toArray();
            $autoevaluaciones_Ids = array_map(function ($autoevaluacion) {
                return $autoevaluacion["id"];
            }, $bloque_cuestionarios);
            $autoevaluaciones = $this->cuestionarios->whereIn('cuestionario_id', $autoevaluaciones_Ids)->groupBy('cuestionario_id');

            foreach ($autoevaluaciones as $key => $autoevaluacion) :
                $usuario_cuestionario = $this->getLastCuestionarioById($key);
                if (!$usuario_cuestionario) continue;

                // 3 Ways - Alexis Bogado
                // Si ya ha hecho el cuestionario se agrega al array aunque haya suspendido o
                // el cuestionario tenga recuperación
                array_push($autoevaluaciones_aprobadas, $autoevaluacion);

            // 3 Ways - Alexis Bogado
            // Comentado ya que el cuestionario de recuperación no es obligatorio para completar un bloque
            // ni tampoco hace falta que el cuestionario esté aprobado.
            // if ($usuario_cuestionario->estado == "suspenso"):
            //     if ($usuario_cuestionario->cuestionario->recuperaciones->count() > 0): // El cuestionario tiene recuperación
            //         foreach ($usuario_cuestionario->cuestionario->recuperaciones as $recuperacion):
            //             $usuario_recuperacion = $this->getLastCuestionarioById($recuperacion->id);
            //             if (!$usuario_recuperacion) continue;

            //             /* if ($usuario_recuperacion->estado == "aprobado") */ array_push($autoevaluaciones_aprobadas, $autoevaluacion);
            //         endforeach;
            //     endif;
            // else: // Cuestionario aprobado
            //     array_push($autoevaluaciones_aprobadas, $autoevaluacion);
            // endif;
            endforeach;
            $items_completed_count = $curso->is_strict ? $items_completed_count : 0;
            $items_bloque_count = $curso->is_strict ? $items_bloque_count : 0;
            return ((count($autoevaluaciones_aprobadas) + $items_completed_count) >= ($items_bloque_count + count($bloque_cuestionarios)));
        endif;
        return ($items_completed_count >= $items_bloque_count);
    }

    // Comprobar si un bloque está completado
    public function completo_bloque($bloque, $bloque_from)
    {
        if ($bloque->id == $bloque_from->id) return true;
        return $this->bloque_completado($bloque);
    }

    // Comprobar si un item está hecho
    public function completo_item($itemId)
    {
        /**
         *  3ways Euro Fuenmayor - Ajuste de criterio para verificar que un usuario ha completado un item de curso
         **  Nuevo criterio: es necesario tener igual o mayor tiempo de visualizado que el tiempo de visualización fijado para el item
         **/
        $is_item_completed = false;
        $usuario_item = UsuarioItem::where('usuario_id', $this->id)->where('item_id', $itemId)->first();
        if ($usuario_item) {
            $is_item_completed = self::is_item_time_completed($usuario_item);
        }
        return $is_item_completed;
    }

    /**
     * 3ways Euro Fuenmayor - Metodo para determinar is un item esta completado
     *  Comparara si el tiempo de visualizado del item es mayor o igual al tiempo de visualizacion fijado para el item
     *  Devuelve true si se cumple condicion, caso contrario, false.
     *  Si el tiempo de visualizaciom es null o 0, siempre devolvera true
     * @param $usuario_item
     * @return bool
     */
    public static function is_item_time_completed($usuario_item)
    {
        $time = $usuario_item->tiempo;
        $time_required_on_minutes = (BloqueItem::find($usuario_item->item_id))->tiempo_visualizacion;
        $time_required_on_seconds = $time_required_on_minutes * 60;
        return is_null($time_required_on_minutes) ? true : ($time_required_on_seconds == 0 ? true : $time >= $time_required_on_seconds);
    }

    // Comprobar si un cuestionario está hecho
    public function completo_cuestionario($cuestionarioId)
    {
        $usuario_cuestionario = UsuarioCuestionario::where('usuario_id', $this->id)->where('cuestionario_id', $cuestionarioId)->first();
        return $usuario_cuestionario;
    }

    /**
     * Comprobar si el usuario ya ha solicitado una clave en un curso
     * 
     * @param Integer $cursoId
     * 
     * @return bool
     */
    public function solicito_clave($cursoId)
    {
        $usuarioCursoSolicitud = $this->cursos_solicitudes->where('curso_id', $cursoId)->first();
        return $usuarioCursoSolicitud ? true : false;
    }

    /**
     * Obtener solicitudes de curso
     * 
     * @return UsuarioCursoSolicitud
     */
    public function cursos_solicitudes()
    {
        return $this->hasMany(UsuarioCursoSolicitud::class, 'usuario_id');
    }

    // Comprobar si queda tiempo para hacer el curso
    public function tiempo_finalizado($curso)
    {
        $usuario_curso = $this->usuario_cursos()->where('curso_id', $curso->id)->first();
        return $usuario_curso->tiempo_finalizado();
    }
    /* Comprobar credenciales sistema antiguo.
    * 
    * @param type $usuario
    * @param type $password
    * 
    * @return User
    */
    public static function checkLoginViejo($email, $pass)
    {
        // recuperar el usuario utilizando el campo password antiguo
        $usuario = User::where([
            ['email', $email],
            ['password', md5($pass)]
        ])->first();

        return $usuario;
    }

    // Comprobar si un usuario está eliminado
    public static function checkEliminado($email)
    {
        $user = User::where([
            ['email', $email],
            ['deleted_at', null]
        ])->first();

        return $user;
    }

    // Obtener encuesta del usuario
    public function encuesta($curso)
    {
        $usuario_encuestas = UsuarioEncuesta::where('usuario_id', $this->id)->where('curso_id', $curso->id)->where('encuesta_id', $curso->encuesta_id);
        if ($usuario_encuestas->count() > 0) return $usuario_encuestas;

        $usuarios_completo_encuestas = UsuarioCompletoEncuesta::where('usuario_id', $this->id)->where('curso_id', $curso->id);
        return $usuarios_completo_encuestas;
    }

    /**
     * Obtener datos básicos del usuario
     * 
     * @return UsuarioDatoBasico
     */
    public function datos_basicos()
    {
        return $this->hasOne(UsuarioDatoBasico::class, 'usuario_id');
    }

    /**
     * Obtener datos profesionales del usuario
     * 
     * @return UsuarioDatoProfesional
     */
    public function datos_profesionales()
    {
        return $this->hasOne(UsuarioDatoProfesional::class, 'usuario_id');
    }

    /**
     * Obtener centro del usuario
     * 
     * @return UsuarioCentro
     */
    public function centros()
    {
        return $this->hasOne(UsuarioCentro::class, 'usuario_id');
    }

    /**
     * Obtener direcciones del usuario
     * 
     * @return UsuarioDireccion
     */
    public function direcciones()
    {
        return $this->hasOne(UsuarioDireccion::class, 'usuario_id');
    }

    /**
     * Obtener especialidades del usuario
     * 
     * @return UsuarioEspecialidad
     */
    public function especialidades()
    {
        return $this->hasMany(UsuarioEspecialidad::class, 'usuario_id');
    }

    /**
     * Comprobar si el usuario tiene una especialidad
     * 
     * @param $especialidad_id (ID de la especialidad)
     * 
     * @return bool
     */
    public function hasEspecialidad($especialidad_id)
    {
        return ($this->especialidades->where('especialidad_id', $especialidad_id)->first() ? true : false);
    }

    /**
     * Obtener datos de socio del usuario
     * 
     * @return UsuarioSocio
     */
    public function socio()
    {
        return $this->hasOne(UsuarioSocio::class, 'usuario_id');
    }

    /**
     * Obtener las áreas de interés del usuario
     * 
     * @return UsuarioInteres
     */
    public function intereses()
    {
        return $this->hasMany(UsuarioInteres::class, 'usuario_id');
    }

    /**
     * Obtener las áreas de interés del usuario
     * 
     * @return UsuarioAreaIntere
     */
    public function area_intereses()
    {
        return $this->belongsToMany(Area::class, 'usuarios_areas_interes', 'usuario_id', 'area_id', 'id', 'id');
    }

    /**
     * Comprobar si el usuario tiene un interés
     * 
     * @param $model_id (ID del area)
     * 
     * @return bool
     */
    public function hasAreaIntereses($model_id)
    {
        return $this->area_intereses()->where('area_id', $model_id)->exists();
    }

    /**
     * Comprobar si el usuario tiene un interés
     * 
     * @param $interes_id (ID del interés)
     * 
     * @return bool
     */
    public function hasInteres($interes_id)
    {
        return ($this->intereses->where('interes_id', $interes_id)->first() ? true : false);
    }

    /**
     * Obtener los cuestionarios del usuario
     * 
     * @return UsuarioCuestionario
     */
    public function cuestionarios()
    {
        return $this->hasMany(UsuarioCuestionario::class, 'usuario_id');
    }

    /**
     * Obtener el último registro usuarios_cuestionarios por cuestionario_id
     * 
     * @param int $cuestionario_id
     * 
     * @return UsuarioCuestionario
     */
    public function getLastCuestionarioById($cuestionario_id)
    {
        return $this->cuestionarios()->where('cuestionario_id', $cuestionario_id)->get()->last();
    }

    /**
     * Obtener todos los usuarios_cuestionarios por cuestionario_id
     * 
     * @param int $cuestionario_id
     * 
     * @return UsuarioCuestionario[]
     */
    public function getTodosCuestionariosById($cuestionario_id)
    {
        return $this->cuestionarios()->where('cuestionario_id', $cuestionario_id)->get();
    }

    /**
     * Obtener datos bancarios del usuario
     * 
     * @return UsuarioDatoBancario
     */
    public function datos_bancarios()
    {
        return $this->hasOne(UsuarioDatoBancario::class, 'usuario_id');
    }

    /**
     * Obtener datos de la tarjeta del usuario
     * 
     * @return UsuarioTarjeta
     */
    public function tarjetas()
    {
        return $this->hasOne(UsuarioTarjeta::class, 'usuario_id');
    }

    /**
     * 3 Ways -Euro Fuenmayor
     * Obtener los diplomas presenciales por evento o todos los del usuario :
     *  Si es por evento requiere un diploma de cualquier actividad del evento o el diploma del evento en si
     *
     *  Criterio de busqueda: DNI -> email -> nombre y apellidos
     *
     * @param null $from_diploma
     * @return DiplomaPresencial[]
     */
    public function diplomas_presenciales($from_diploma = null)
    {
        $query_array = [];
        if ($from_diploma) {
            array_push($query_array, ['created_at', '=', explode(' ', $from_diploma->created_at->toDateTimeString())[0]]);
        }
        $dni = $from_diploma ? trim($from_diploma->dni) : trim($this->dni);
        $email = $from_diploma ? trim($from_diploma->email) : trim($this->email);
        $nombre = $from_diploma ? $from_diploma->nombre : $this->nombre;
        $apellidos = $from_diploma ? $from_diploma->apellidos : $this->apellidos;
        $acreditaciones_diploma = collect();
        if ($dni != '' && $dni != 'no-disponible') {
            array_push($query_array, ['dni', 'like', "%{$dni}%"]);
            $acreditaciones_diploma = DiplomaPresencial::where($query_array);
        }
        if ($email != '') {
            if ($acreditaciones_diploma->count() == 0) {
                $acreditaciones_diploma = DiplomaPresencial::where('email', 'like', "%$email%");
            } else {
                $acreditaciones_diploma = $acreditaciones_diploma->orwhere('email', 'like', "%$email%");
            }
        }
        if ($acreditaciones_diploma->count() == 0) {
            array_push($query_array, ['nombre', 'like', "%{$nombre}%"]);
            array_push($query_array, ['apellidos', 'like', "%{$apellidos}%"]);
            $acreditaciones_diploma = DiplomaPresencial::where($query_array);
        }
        
        return $acreditaciones_diploma->count() > 0 ? $acreditaciones_diploma->orderBy('created_at', 'asc')->get() : $acreditaciones_diploma;
    }

    /**
     * 3 ways - Euro Fuenmayor
     *
     *  Devuelve contenido de la tabla de actividades y los creditos totales de un evento para diplomas y listado de formacion presencial
     *
     * @param $from_diploma
     * @return array
     */
    public function tabla_de_actividades_y_creditos_por_evento_diploma_presencial($from_diploma)
    {
        $year = explode(' ', $from_diploma->created_at->toDateTimeString())[0];
        $single = $year > 2017;
        $acreditaciones_diploma = $this->diplomas_presenciales($from_diploma);
        $acreditacion_evento = $from_diploma->acreditacion_evento;
        $duplicated_check = [];
        $total_creditos = 0.00;
        $cells = '';
        foreach ($acreditaciones_diploma as $diploma) {
            if ($single) {
                $diploma = $from_diploma;
            }
            $file_pdf = public_path() . "/storage/diplomas/presenciales/Diploma_{$diploma->id_evento} {$diploma->id}.pdf";
            if (!file_exists($file_pdf)) {
                if (floatval($diploma->creditos) > 0) {
                    $year_acreditacion = explode(' ', $diploma->created_at->toDateTimeString())[0];
                    $num_expediente = $diploma->num_expediente == '' ? '--' : $diploma->num_expediente;
                    if (!empty($acreditacion_evento)) {
                        $sesion = strtolower($diploma->sesion) == 'asistencia' ? $acreditacion_evento->nombre : $diploma->sesion;
                        if (!in_array("{$sesion},{$diploma->creditos},{$num_expediente}", $duplicated_check) && $year_acreditacion == $year) {
                            $cells .= "
                                  <tr style=\"text-align:center;\">
                                    <td>{$sesion}</td>
                                    <td>{$diploma->creditos}</td>
                                    <td>{$num_expediente}</td>
                                  </tr>
                                ";
                            $total_creditos += $diploma->creditos;
                        }
                        if (!$single) {
                            array_push($duplicated_check, "{$sesion},{$diploma->creditos},{$num_expediente}");
                        }
                    }
                }
            }
            if ($single) {
                break;
            }
        }
        return [$cells, $total_creditos];
    }

    /*******************************************************************************
    // PARAMETROS ORIGINALES DESHABILITADOS TEMPORALMENTE HASTA RESOLVER CONFLICTOS
     *******************************************************************************/
    //     // para marcar las instancias de Carbon
    //     protected $dates = [
    //         'fecha_reg',
    //         'nacimiento',
    //         'fecha_cargo'
    //     ];

    //     /**
    //      * Datos relacionados del usuario
    //      * 
    //      * @var array
    //      */
    // protected $with = ['perfiles', 'datos_socio', 'areas_conocimiento', 'areas_conocimiento_otras', 'areas_interes', 'areas_interes_otras']; // relación con perfiles
    // protected $appends = ['socio', 'tipo_socio']; // atributos calculados

    //     /**
    //      * The attributes that are mass assignable.
    //      *
    //      * @var array
    //      */
    //     protected $fillable = [
    //         'tratamiento', 'nombre', 'apellidos', 'dni', 'email', 'password',
    //     ];

    //     /**
    //      * The attributes that should be hidden for arrays.
    //      *
    //      * @var array
    //      */
    //     protected $hidden = [
    //         'password', 'remember_token',
    //     ];

    //     /**
    //      * Atributos personalidos
    //      *
    //      * @var integer
    //      */
    //     protected $idSepd = 2082;

    /***************************************************
     * Relaciones con otros modelos
     *
     ***************************************************/

    /**
     * Lista de perfiles del usuario
     */
    // public function perfiles() {

    //     return $this->belongsToMany('App\Perfil', 'usuarios_info', 'id_usuario', 'id_perfil');

    // }

    /**
     * Datos de socio del usuario
     */
    // public function datos_socio() {
    //     return $this->hasOne(UsuarioSocio::class, 'usuario_id');
    // }

    // /**
    //  * Áreas de conocimiento del usuario
    //  */
    // public function areas_conocimiento() {

    //     return $this->hasMany('App\Area', 'usuario_id', 'usuario_id')->where('tipo', 'conocimiento')->orderBy('n');

    // }

    // /**
    //  * Otras áreas conocimiento del usuario
    //  */
    // public function areas_conocimiento_otras() {

    //     return $this->hasMany('App\Area', 'id_usuario', 'id_usuario')->where('tipo', 'otros conocimientos')->orderBy('n');

    // }

    // /**
    //  * Áreas de interes del usuario
    //  */
    // public function areas_interes() {

    //     return $this->hasMany('App\Area', 'id_usuario', 'id_usuario')->where('tipo', 'interes')->orderBy('n');

    // }

    // /**
    //  * Áreas de interes del usuario
    //  */
    // public function areas_interes_otras() {

    //     return $this->hasMany('App\Area', 'id_usuario', 'id_usuario')->where('tipo', 'otros intereses')->orderBy('n');

    // }

    //     /***************************************************
    //      * Atributos calculados!
    //      *
    //      ***************************************************/

    //     /**
    //      * Es socio el usuario?
    //      * 
    //      * @return bool
    //      */
    //     public function getSocioAttribute()
    //     {
    //         // Comprobar que tiene el perfil SOC
    //         $socio = DB::table('perfiles_usuarios')
    //                     ->where('perfiles_usuarios.id_perfil', '=', 'SOC')
    //                     ->where('perfiles_usuarios.id_usuario', '=', $this->id_usuario)
    //                     ->first();

    //         return ($socio) ? true : false;
    //     }

    //     /**
    //      * Tiene como contacto a SEPD?
    //      * 
    //      * @return bool
    //      */
    //     public function getcontactoSEPDAttribute()
    //     {
    //         // Comprobar si tiene como contacto el usuario SEPD
    //         $contacto = DB::table('contactos')
    //                         ->where('id_usuario_1', '=', $this->id_usuario)
    //                         ->where('id_usuario_2', '=', $this->idSepd)
    //                         ->first();

    //         return ($contacto) ? true : false;
    //     }

    //     /**
    //      * Nombre completo
    //      * 
    //      * @return string
    //      */
    //     public function getNombreCompletoAttribute()
    //     {

    //         return $this->tratamiento." ".$this->nombre." ".$this->apellidos;
    //     }

    /**
     * Tipo socio
     * 
     * @return string
     */
    public function getTipoSocioAttribute()
    {
        if (!$this->es_socio()) return null;

        return $this->socio->tipo;
    }

    //     /***************************************************
    //      * Funciones añadidas a usuario
    //      *
    //      ***************************************************/

    //     /**
    //      * Crear contacto a SEPD
    //      * 
    //      * @return App\Contacto
    //      */
    //     public function creaContactoSepd()
    //     {
    //         // insertar a este usuario como contacto de la SEPD
    //         $contacto = \App\Contacto::create([
    //                         'id_usuario_1' => $this->id_usuario,
    //                         'id_usuario_2' => $this->idSepd,
    //                         'estado' => 1,
    //                         'id_usuario_sugerido' => 0,
    //                         'mensaje' => ''
    //                     ]);

    //         return ($contacto) ? true : false;
    //     }

    //     /**
    //      * Actualiza los datos que se pueden modificar en el registro
    //      * 
    //      * @param $datos (request)
    //      * @param $password
    //      */
    //     public function actualizaDatosRegistro($datos, $password)
    //     {

    //         // encriptar password
    //         $this->pass = md5($password);
    //         $this->password = Hash::make($password);
    //         // los demás datos
    //         $this->email = $datos['correo'];
    //         $this->centro = $datos['centro'];
    //         $this->especialidad = $datos['especialidad'];
    //         $this->fecha_reg = date('Y-m-d H:i:s');

    //         $this->save();
    //     }

    //     /**
    //      * Borra todas las areas del usuario relativas al perfil
    //      * 
    //      * @return void
    //      */
    //     public function borrarAreasPerfil()
    //     {
    //         // tipo "conocimiento", "interes" y "otros conocimientos"
    //         DB::table('areas')->where('id_usuario', $this->id_usuario)
    //                             ->where(function ($query) {
    //                                         $query->where('tipo', 'conocimiento')
    //                                               ->orWhere('tipo', 'interes')
    //                                               ->orWhere('tipo', 'otros conocimientos')
    //                                               ->orWhere('tipo', 'otros intereses');
    //                                     })
    //                             ->delete();
    //     }

    /**
     * Send the password reset notification.
     * Sobrescribir \Illuminate\Auth\Passwords\CanResetPassword.php para la traducción
     * ResetPasswordNotification es App\Notifications\ResetPassword
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    //     /***************************************************
    //      * Otras funciones dedicadas, normalmente static
    //      *
    //      ***************************************************/

    //     /**
    //      * Comprobar credenciales sistema antiguo.
    //      * 
    //      * @param type $usuario
    //      * @param type $password
    //      * 
    //      * @return User
    //      */
    //     public static function checkLoginViejo($email, $password) {
    //         // recuperar el usuario utilizando el campo password antiguo
    //         $usuario = User::where([
    //                         ['email', $email],
    //                         ['pass', md5($password)]
    //         ])->first();

    //         return $usuario;
    //     }

    //     /**
    //      * Inicia sesión en el servidor antiguo
    //      * 
    //      * @return void
    //      */
    public function crearSesionAntigua()
    {
        //         // guardar en la sesión las variables utilizadas en la web antigua para conectar

        //         // Según conexion.php del sistema antiguo
        //         // recuperar lista de perfiles del usuario:
        //         // si hay alguno -> crear sesión
        //         $permisos = [];
        //         foreach($this->perfiles as $perfil) {
        //             $permisos[] = $perfil->id_perfil;
        //         }

        //         if (!empty($permisos)) {
        //             // Guardar variables de sesión
        // session_start no hace falta ya que se arranca siempre en el AuthServiceProvider
        $_SESSION['sepd_link_user'] = $this->id;
        $_SESSION['sepd_link_nombre'] = utf8_decode(ucfirst(strtolower($this->nombre)));
        // $_SESSION['sepd_link_sexo'] = $this->sexo;
        // $_SESSION['sepd_link_socio'] = true;
        // $_SESSION['sepd_permisos'] = $permisos;
        //         }
    }

    //     /**
    //      * Recuperar un usuario según una serie de valores
    //      * 
    //      * @return user
    //      */
    //     public static function getUserByFiltros(array $filtros) {

    //         $query = static::where($filtros);

    //         return $query->first();

    //     }

    /* Formatear una fecha en español
     * 
     * @param fecha -> fecha a formatear
     * @param formato -> por defecto j F Y (carbon)
     * @returns string
     */
    public function formatea_fecha($campo, $formato = 'j F Y')
    {

        Date::setLocale('es');

        $objeto = new Date($this->{$campo});
        return ucfirst($objeto->format($formato));
    }

    public function usuarios_cargos()
    {
        return $this->hasMany(UsuariosCargos::class, 'usuario_id', 'id');
    }

    public function cargos() {
        return $this->hasMany(UsuariosCargos::class, 'usuario_id');
    }

    /**
     * Encrypt an array
     *
     * @param array $data
     *
     * @return string
     */
    function encrypt(array $data)
    {
        $json_data = json_encode($data);

        return openssl_encrypt($json_data, self::HASH_METHOD, self::HASH_KEY);
    }

    public function getUrlManualGestivosSepdAttribute ()
    {
        // if ($this->socio) {
            $url = 'https://manualdigestivosepd.es/loginin/?token=';
            
            $encrypted = $this->encrypt([
                'email' => $this->email,
                'firstname' => $this->nombre,
                'lastname' => $this->apellidos,
                'user_dni' => $this->dni,
                'origen' => 'sepd'
            ]);

            return $url . urlencode($encrypted);
        // }

        return;
    }

    public function enlaceSSO ($enlace)
    {
        return $enlace . $this->getEncryptManualGestivosAttribute();
    }

    public function getEncryptManualGestivosAttribute ()
    {
        $url = '?token=';

        // if ($this->socio) {
            
            $encrypted_data = $this->encrypt([
                'firstname' => $this->nombre,
                'lastname' => $this->apellidos,
                'email' => $this->email,
                'user_dni' => $this->dni
            ]);
            
            $base64_encrypted_data = base64_encode($encrypted_data);

            return $url . urlencode($base64_encrypted_data);
        // }

        return $url;
    }
}
