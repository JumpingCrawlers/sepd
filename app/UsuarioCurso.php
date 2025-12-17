<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Curso;
use DateTime;
use Illuminate\Support\Facades\Auth;

class UsuarioCurso extends Model
{
    protected $table = 'usuarios_cursos';

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * 3 Ways - Alex
     * Obtener mensajes del usuario siendo emisor
     */
    public function mensajes()
    {
        $usuario_id = $this->usuario_id;
        return $this->hasMany(Mensaje::class, "tema", "curso_id")->where(function ($query) use ($usuario_id) {
            $query->where('emisor', '=', $usuario_id)
                ->orWhere('receptor', '=', $usuario_id);
        })->orderByDesc('created_at');
    }
    /**
     * 3 Ways - Alex
     * Obtener mensajes del usuario siendo emisor
     */
    public function mensajesPorUsuario($id)
    {
        $usuario_id = $this->usuario_id;
        return $this->hasMany(Mensaje::class, "tema", "curso_id")->where(function ($query) use ($usuario_id, $id) {
            $query->where([['emisor', '=', $usuario_id], ['receptor', '=', $id]])
                ->orWhere([['receptor', '=', $usuario_id], ['emisor', '=', $id]]);
        })->orderBy('created_at');
    }

    /* 3 Ways - Alexis Bogado */

    // Obtener el progreso del curso
    public function items_vistos()
    {
        $items_Ids = array_map(function ($item) {
            return $item["id"];
        }, $this->curso->items_totales()->toArray());

        /**
         *  3ways Euro Fuenmayor - Agregado filtro, para que en caso de que el curso sea estricto,
         *  solo se devuelvan los items que cumplan con el tiempo minimo de visualizacion para ser considerado como visto
         **/
        if ($this->curso->is_strict) {
            $user = User::find(Auth::id());
            $usuario_items = UsuarioItem::where('usuario_id', $user->id)->whereIn('item_id', $items_Ids)->get();
            $items_Ids = [];
            foreach ($usuario_items as $index => $usuario_item) {
                $is_item_completed = $user::is_item_time_completed($usuario_item);
                if ($is_item_completed) {
                    array_push($items_Ids, $usuario_item->item_id);
                }
            }
        }

        return UsuarioItem::where('usuario_id', $this->usuario_id)->whereIn('item_id', $items_Ids);
    }

    /**
     * 3WAYS Roma Bilibov
     * Obtener la fecha de la actividad del usuario en el curso
     */
    public function fecha_ultima_actividad()
    {
        // Averiguamos si el curso tiene cuestionario
        if (count($this->autoevaluaciones_hechas()) > 0) {
            return $this->autoevaluaciones_hechas()[0][0]['created_at'];
        }

        // En el caso de que no haya cuestionario en el curso sacamos la fecha del ultimo item del curso
        $usuario_items = $this->items_vistos()->orderBy('created_at')->get();
        // Averiguamos si el usuario a accedido a los items del curso
        if (count($usuario_items) > 0) {
            // La fecha fin será la fecha del último item
            $usuario_item = $usuario_items[count($usuario_items) - 1];
            
            return $usuario_item->created_at;
        }

        return null;
    }

    /**
     * Obtener las evaluaciones completadas de un usuario
     * 
     * @return array
     */
    public function autoevaluaciones_hechas()
    {
        $autoevaluaciones_hechas = [];

        $autoevaluaciones_Ids = array_map(function ($autoevaluacion) {
            return $autoevaluacion["id"];
        }, $this->curso->autoevaluaciones()->toArray());
        $autoevaluaciones = $this->usuario->cuestionarios->whereIn('cuestionario_id', $autoevaluaciones_Ids)->groupBy('cuestionario_id');

        foreach ($autoevaluaciones as $key => $autoevaluacion) :
            $usuario_cuestionario = $this->usuario->getLastCuestionarioById($key);
            if (!$usuario_cuestionario) continue;

            if ($usuario_cuestionario->estado == "suspenso") :
                if ($usuario_cuestionario->cuestionario->recuperaciones->count() <= 0 && $this->usuario->cuestionarios->where('cuestionario_id', $key)->count() >= $usuario_cuestionario->cuestionario->oportunidades) : // No tiene recuperación y no quedan intentos
                    array_push($autoevaluaciones_hechas, $autoevaluacion);
                elseif ($usuario_cuestionario->cuestionario->recuperaciones->count() > 0) : // El cuestionario tiene recuperación
                    foreach ($usuario_cuestionario->cuestionario->recuperaciones as $recuperacion) :
                        $usuario_recuperacion = $this->usuario->getLastCuestionarioById($recuperacion->id);

                        if (!$usuario_recuperacion) continue;

                        if ($usuario_recuperacion->estado == "aprobado" || ($usuario_recuperacion->estado == "suspenso" && $this->usuario->cuestionarios->where('cuestionario_id', $recuperacion->id)->count() >= $recuperacion->oportunidades)) // Cuestionario aprobado o suspenso y no quedan intentos
                            array_push($autoevaluaciones_hechas, $autoevaluacion);
                    endforeach;
                endif;
            else : // Cuestionario aprobado
                array_push($autoevaluaciones_hechas, $autoevaluacion);
            endif;
        endforeach;

        return $autoevaluaciones_hechas;
    }

    // Obtener porcentaje del progreso
    public function porcentaje_progreso()
    {
        $itemsCompletados = $this->items_vistos()->count();
        $itemsTotales = $this->curso->items_totales()->count();

        $autoevaluacionesTotales = $this->curso->autoevaluaciones()->count();
        $autoevaluacionesCompletadas = count($this->autoevaluaciones_hechas());

        if ($autoevaluacionesTotales > 0) return round((($autoevaluacionesCompletadas + $itemsCompletados) * 100) / ($autoevaluacionesTotales + $itemsTotales));
        elseif ($itemsTotales > 0) return round(($itemsCompletados * 100) / $itemsTotales);
        else return 100;
    }

    // Comprobar si el usuario puede seguir haciendo el curso
    public function tiempo_finalizado()
    {
        $hoy = new DateTime();
        $tiempo_finalizado = true;

        if ($this->curso->periodo && $this->curso->periodo > 0) {
            $fecha_fin = date("d-m-Y", strtotime($this->created_at . "+" . $this->curso->periodo . " days"));
            $fecha_fin = new DateTime(date('Y-m-d', strtotime($fecha_fin . ' + 1 days')));

            if ($fecha_fin >= $hoy) $tiempo_finalizado = false;
        } elseif ($this->curso->fecha_fin && $this->curso->fecha_fin > 0) {
            if (new DateTime(date('Y-m-d', strtotime($this->curso->fecha_fin . ' + 1 days'))) >= $hoy) $tiempo_finalizado = false;
        } else // Tiempo ilimitado
            $tiempo_finalizado = false;

        return $tiempo_finalizado;
    }

    // Obtener los días restantes para finalizar el curso
    public function dias_restantes()
    {
        $hoy = time();
        $fecha_fin = strtotime(date('Y-m-d', strtotime($this->curso->fecha_fin . ' + 1 days')));

        return round(($fecha_fin - $hoy) / 60 / 60 / 24);
    }

    /**
     * 3 Ways - Alexis Bogado
     * Obtener el usuario
     * 
     * @return \App\User
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * 3 Ways - Alexis Bogado
     * Comprobar si el usuario puede obtener el diploma del curso
     * 
     * @return bool
     */
    public function obtiene_diploma()
    {
        //Si no es un curso, sino una publicacón o recurso multimedia, que no se conceda un diploma
        if ($this->curso->formato != 'curso')
            return false;
        // Configuración: AUTOEVALUACIÓN FINAL
        if ($this->curso->configuracion(2) || $this->curso->configuracion(6)) :
            $autoevaluaciones = $this->curso->autoevaluaciones()->where('categoria_id', 3);
            $recuperaciones = $this->curso->autoevaluaciones(true)->where('categoria_id', 4);
            if ($autoevaluaciones->count() > 0) :
                $obtiene_diploma = false;

                foreach ($autoevaluaciones as $autoevaluacion) :
                    $usuario_cuestionario = $this->usuario->getLastCuestionarioById($autoevaluacion->id);
                    $usuario_recuperacion_aprobada = false;

                    foreach ($recuperaciones as $recuperacion) :
                        $usuario_recuperacion = $this->usuario->getLastCuestionarioById($recuperacion->id);
                        if ($usuario_recuperacion && $usuario_recuperacion->isApproved()) {
                            $usuario_recuperacion_aprobada = true;
                            break;
                        }
                    endforeach;
                    if (!$usuario_cuestionario) return false;
                    elseif (!$usuario_cuestionario->isApproved() && !$usuario_recuperacion_aprobada) return false;
                    else $obtiene_diploma = true;
                endforeach;

                if ($obtiene_diploma) return true;
            endif;
        endif;

        // Configuración: CURSO ESTRICTO
        if ($this->curso->configuracion(1)) :
            if ($this->items_vistos()->count() < $this->curso->items_totales()->count()) return false;
            if (count($this->autoevaluaciones_hechas()) >= $this->curso->autoevaluaciones()->count()) :
                $autoevaluaciones = $this->curso->autoevaluaciones();
                if ($autoevaluaciones->count() > 0) :
                    $obtiene_diploma = false;
                    $autoevaluaciones = collect($autoevaluaciones)->sortBy('categoria_id');
                    foreach ($autoevaluaciones as $autoevaluacion) :
                        if ($autoevaluacion->categoria_id != 4 && $obtiene_diploma == true) {
                            break;
                        }
                        $usuario_cuestionario = null;
                        //Revisar si hay algún cuestionario aprobado con ese cuestionario_id para ese usuario
                        $cuestionarios_usuario = $this->usuario->getTodosCuestionariosById($autoevaluacion->id);
                        foreach ($cuestionarios_usuario as $cuestionario_usuario) {
                            if ($cuestionario_usuario->estado == "aprobado") {
                                $usuario_cuestionario = $cuestionario_usuario;
                                break;
                            }
                        }

                        if (!$usuario_cuestionario) {
                            $usuario_cuestionario = $this->usuario->getLastCuestionarioById($autoevaluacion->id);
                        }
                        if (!$usuario_cuestionario) {
                            return false;
                        } elseif (!$usuario_cuestionario->isApproved()) {
                            return false;
                        } else {
                            $obtiene_diploma = true;
                        }
                    endforeach;
                    if ($obtiene_diploma) return true;
                endif;
            else :
                return false;
            endif;
        endif;

        // Comprobar cuestionarios
        if (count($this->autoevaluaciones_hechas()) < $this->curso->autoevaluaciones()->count())
            return false;

        // Comprobar items
        /**
         *  3ways Euro Fuenmayor - Ajuste de criterio para verificar que un usuario ha completado un bloque de curso
         *  Nuevo criterio: se revisan los items completados si el curso es estricto
         **/
        if (($this->items_vistos()->count() < $this->curso->items_totales()->count()) && $this->curso->is_strict) return false;
        else return true;
    }

    /***********************
     *  3 Ways - Alex R. 
     *  Obtener expediente del usuario_curso.
     */
    public function expediente()
    {
        return $this->belongsTo('App\CursoExpediente', 'curso_expediente_id');
    }
}
