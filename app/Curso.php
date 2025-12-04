<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
// para la query directa a países
use DB;
// para el formateo de fechas en español
use Jenssegers\Date\Date;
// para formacion
use Illuminate\Database\Eloquent\Model;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;


class Curso extends Model
{
    // Accessors:
    protected $appends = ['descripcion_estado', 'ruta_imagen', 'fecha_formateada', 'fecha_creacion_formateada', 'fechas_formateadas', 'configuracion_socios'];

    /**
     * Obtener los cursos disponibles para inscripción
     * @return query
     */
    public function scopeDisponibles($query)
    {

        $query = $query->where('estado', 1)->whereNull('proyecto_formativo_id')
        ->where(function ($query) {
            $query->where('fecha_inicio', '>', 0)
                  ->orWhere('formato', 'publicacion')
                  ->orWhere('formato', 'recurso');
        })/*->where(function ($query) {
            $query->where('fecha_fin', '>=', 'CURDATE()')
                //   ->orWhere('acceso_clave', '!=', 1)
                  ->orWhere('formato', '=', 'publicacion');
        })*/->where(function ($query) {
                    $query->whereNull('deleted_at');   
        })->orderBy('fecha_inicio', 'desc');

        return $query;
    }

    /**
     * Query Scope para devolver solo páginas marcadas como visibles
     */
    public function ScopeParaBiblioteca($query) {
        
        return $query->selectRaw("id AS id_documento, titulo, '' AS autor, fecha_inicio AS fecha, descripcion, 
	estado AS tipo, id AS enlace, id AS archivo_biblio, created_at, fecha_inicio");

    }


    // /*****************************************************************
    //  * ACCESSORS: Atributos calculados
    //  * 
    //  *
    //  *****************************************************************/

    // /**
    //  * Devuelte la descripción del estado del curso
    //  * @return string
    //  */
    // public function getDescripcionEstadoAttribute() {
        
    //     $estadoTxt = '';

    //     $fechaHoraActual = date("Y-m-d H:i:s");

    //     // según detalles del curso
    //     if (($this->publicado) == 0) {
    //         $estadoTxt = "Pendiente de lanzamiento";
    //     } elseif ($this->gratis_socios == 1) {
    //         $estadoTxt = "Curso exclusivo para socios";
    //     } elseif ($this->formato == 'publicacion') {
    //         $estadoTxt = "Publicación";
    //     } elseif ($this->fecha_inicio->timestamp > 0) {
    //         if($this->fecha_fin->timestamp > 0) {
    //             if($this->fecha_hora >= $this->fecha_inicio && $fechaHoraActual <= $this->fecha_fin) {
    //                 if($this->precio_socio == 0) {
    //                     if($this->precio_no_socio == 0) {
    //                         $estadoTxt = "Curso abierto";
    //                     } else {
    //                         $estadoTxt = "Curso abierto para socios";
    //                     }
    //                 } else {
    //                     $estadoTxt = "Curso activo";
    //                 }
    //             } elseif ($fechaHoraActual < $this->fecha_inicio) {
    //                 if ($this->fecha_matriculacion->timestamp > 0 && $fechaHoraActual >= $this->fecha_matriculacion) {
    //                     $estadoTxt = "Matrícula abierta";
    //                 } else {
    //                     $estadoTxt = "Próxima convocatoria";
    //                 }
    //             } elseif($fechaHoraActual > $this->fecha_fin) {
    //                 if($this->acceso_clave) {
    //                     $estadoTxt = "Curso cerrado";
    //                 } else {
    //                     $estadoTxt = "Webcast gratis";
    //                 }
    //             }
    //         } else {
    //             if($fechaHoraActual >= $this->fecha_inicio) {
    //                 $estadoTxt = "Curso activo";
    //             } elseif($fechaHoraActual < $this->fecha_inicio) {
    //                 if($this->fecha_matriculacion->timestamp > 0 && $fechaHoraActual >= $this->fecha_matriculacion) {
    //                     $estadoTxt = "Matrícula abierta";
    //                 } else {
    //                     $estadoTxt = "Próxima convocatoria";
    //                 }
    //             }
    //         }
    //     } else {
    //         $estadoTxt = "Pendiente de lanzamiento";
    //     }
        
    //     return $estadoTxt;
    // }

    /**
     * Devuelve la ruta de la imagen del curso
     * @return string de ruta
     */
    public function getRutaImagenAttribute() {
        $ruta = url(config('app.url_back')).'storage/'.$this->imagen;
        return $ruta;
    }

    /**
     * Devuelve las fechas del curso formateadas en español
     * @return array de fechas
     */
    public function getFechasFormateadasAttribute() {
        
        Date::setLocale('es');
        
        $fechas = array();
        
        foreach ($this->dates as $fecha) {
            $fecha_tmp = new Date($this->{$fecha});
            $fecha_tmp = ucfirst($fecha_tmp->format('l j F Y H:i'));
            // quitar 00:00 si es el caso
            if (substr($fecha_tmp, -5) == '00:00') {
                $fecha_tmp = substr($fecha_tmp, 0, -5);
            }
            $fechas[$fecha] = $fecha_tmp;
        }
        
        return $fechas;
    }
    
    /**
     * Devuelve la fecha de inicio formateada
     * Necesaria para la biblioteca, que recupera los cursos y ha de coincidir
     * con Documentos, que tiene este atributo
     * @return string
     */
    public function getFechaFormateadaAttribute() {

        Date::setLocale('es');
        
        $fecha = new Date($this->fecha_inicio);

        return ucfirst($fecha->format('j F Y'));
    }


    /**
     * Devuelve la fecha de creación formateada
     * Necesaria para la biblioteca, que recupera los cursos y ha de coincidir
     * con Documentos, que tiene este atributo
     * @return string
     */
    public function getFechaCreacionFormateadaAttribute() {

        Date::setLocale('es');
        
        $fecha = new Date($this->created_at);

        return ucfirst($fecha->format('j F Y'));
    }
    

    // /***************************************************
    //  * Otras funciones dedicadas, normalmente static
    //  *
    //  ***************************************************/

    /**
     * Cursos acreditados
     * @return collection Cursos
     */
    public static function acreditados() {

        $fecha_tope = date("Y-m-d", (time()-23*60*60))." 23:59:59";
        $cursos = static::where(function ($query) use ($fecha_tope) {
                                $query->whereNull('fecha_fin')
                                      ->orwhere('fecha_fin', '>=', $fecha_tope);
                     })->where('acreditado','=', 1)->whereNull("proyecto_formativo_id");
        return $cursos;
        
    }
    
    /**
     * Recursos multimedia (antes Cursos gratuitos)
     * @return collection Cursos
     */
    public static function multimedia() {

        $fecha_tope = date("Y-m-d", (time()-23*60*60))." 23:59:59";
        // $cursos = static::where('acceso_clave','=',0)
        //                 ->where('gratis_socios', '=', 0)
        $cursos = static::where('formato', '=', 'recurso')->where(function ($query) use ($fecha_tope) {
                            $query->where(function ($query) use ($fecha_tope) {
                                        $query->whereNull('fecha_fin')
                                              ->orwhere('fecha_fin', '>=', $fecha_tope)
                                              ->where('precio_socio', '=', 0)
                                              ->where('precio_nosocio', '=', 0);
                                })->orWhere('fecha_fin', '<', $fecha_tope)->whereNull("proyecto_formativo_id");
                      });

        return $cursos;
    }

    /**
     * Diferentes años con curso
     * @return collection Años
     */
    public static function anyos() {

        // alias de las columnas para homogeneizar filtros
        return self::withoutGlobalScopes()
                ->selectRaw('DISTINCT YEAR(fecha_inicio) as id, YEAR(fecha_inicio) as nombre')
                ->where('estado', 1)
                ->where('fecha_inicio', '>', 0)
                ->orderBy('id', 'desc')
                ->get();

    }

    /**
     * Diferentes países usados en cursos
     * @return collection Países
     */
    public static function paises() {

        return DB::table('paises')
                ->selectRaw('iso3 as id, nombre')
                ->where('usado_cursos', '>', 0)
                ->orderBy('nombre')
                ->get();

    }
    
    /**
     * Lista de cursos filtrados según parámetros del request (API)
     * @return collection Cursos
     */
    public static function filtrados($request) {

        // Montamos la query sin filtros
        
        $query = Curso::disponibles();
        
        // Iniciamos montando el filtro según el tipo de cursos
        $fecha_tope = date("Y-m-d", (time()-23*60*60))." 23:59:59";
        switch($request->tipo) {
            case "acreditados":
                $query = $query->where(function ($query) use ($fecha_tope) {
                                        $query->whereNull('fecha_fin')
                                              ->orwhere('fecha_fin', '>=', $fecha_tope);
                         })->where('acreditado','=', 1)->whereNull("proyecto_formativo_id");
                break;
            case "multimedia":
                // $query = $query->where('acceso_clave','=',0)
                //                ->where('gratis_socios', '=', 0)
                $query = $query->where('formato', '=', 'recurso')->where(function ($query) use ($fecha_tope) {
                                        $query->where(function ($query) use ($fecha_tope) {
                                                $query->whereNull('fecha_fin')
                                                      ->orwhere('fecha_fin', '>=', $fecha_tope)
                                                      ->where('precio_socio', '=', 0)
                                                      ->where('precio_nosocio', '=', 0);
                                        })->orWhere('fecha_fin', '<', $fecha_tope)->whereNull("proyecto_formativo_id");
                               });                
                break;
            case "gratis":
                // $query = $query->where('acceso_clave','=',0)
                //                ->where('gratis_socios', '=', 1)
                $query = $query->where(function ($query) use ($fecha_tope) {
                                        $query->where(function ($query) use ($fecha_tope) {
                                                $query->whereNull('fecha_fin')
                                                      ->orwhere('fecha_fin', '>=', $fecha_tope)
                                                      ->where('precio_socio', '=', 0)
                                                      ->where('precio_nosocio', '=', 0);
                                        })->orWhere('fecha_fin', '<', $fecha_tope)->whereNull("proyecto_formativo_id");
                               });                
                break;
            default:
                // nada, por defecto son los disponibles
                break;
        }
        
        
        // Recorrer la lista de parámetros y montar la query
        // Las areas, los formatos, los paises, los guardamos separados para montar los OR
        $areas = array();
        $formatos = array();
        $anyos = array();
        $paises = array();

        foreach ($request->all() as $filtro => $valor) {
            // los parámetros tienen el id en el nombre -> recuperarlo
            $variable = preg_split('/_/',$filtro);
            switch (strstr($filtro, "_", true)) {
                case "texto":
                    $query = $query->where(function ($query) use ($valor) {
                                    $query->where('titulo', 'like', '%' . $valor . '%')
                                          ->orWhere('etiquetas', 'like', '%' . $valor . '%');
                             });
                    break;
                case "areas":
                    $areas[] = $variable[1];
                    break;
                case "formatos":
                    $formatos[] = $variable[1];
                    break;
                case "anyos":
                    $anyos[] = $variable[1];
                    break;
                case "paises":
                    $paises[] = $variable[1];
                    break;
                default:
                    // page/tipo -> nada
                    break;
         
            }
        }
        
        // Montar el resto de la query
        if (!empty($areas)) {
             $lista_areas = implode("','",$areas);
             $query = $query->whereRaw("id IN (SELECT curso_id FROM cursos_areas WHERE area_id IN ('".$lista_areas."'))")->where('estado', 1);
        }
        if (!empty($formatos)) {
            $lista_formatos = implode("','",$formatos);
            $query = $query->whereRaw("formato IN ('".$lista_formatos."')");
        }
        if (!empty($anyos)) {
            $lista_anyos = implode("','",$anyos);
            $query = $query->whereRaw("YEAR(fecha_inicio) IN ('".$lista_anyos."') AND fecha_inicio IS NOT NULL")->orWhereRaw("formato != 'curso' AND YEAR(created_at) IN ('".$lista_anyos."') AND fecha_inicio IS NULL")->where('estado', 1);
        }
        if (!empty($paises)) {
            $lista_paises = implode("','",$paises);
            $query = $query->whereRaw("pais IN ('".$lista_paises."')");
        }
        

        
        $proyectos = ProyectoFormativo::orderBy('created_at', 'DESC')->where('activo',1)
                ->get()
                ->map(function ($proyecto){
                    $cursoMasAntiguo = Curso::where("proyecto_formativo_id",$proyecto->id)->orderBy("fecha_inicio","ASC")->first();
                    return (object)[
                        'id' => 'proyecto/'.$proyecto->id,
                        'titulo' => $proyecto->nombre,
                        'ruta_imagen' => rtrim(config('app.url_back'), '/') . '/storage/' . $proyecto->imagen,
                        'imagen' => $proyecto->imagen,
                        'fecha_creacion_formateada' => $cursoMasAntiguo->getFechaFormateadaAttribute(),
                        'formato' => 'proyecto',
                        'descripcion' => '',
                        'acreditado' => false,
                        'descripcion_estado' => 'Proyecto formativo',
                        'descripcion_contenido' => null,
                        'configuracion_socios' => false,
                        'creditos' => 0,
                        'fecha_fin' => null,
                        'periodo' => $proyecto->created_at->format('d/m/Y'),
                    ];
                });
              
            $cursos = $query->get();

            // Combinar ambas colecciones
            $combinado = $proyectos->concat($cursos);

            // Paginar manualmente la colección combinada
            $page = request()->get('page', 1);
            
            $perPage = setting('site.elementos_pagina');
            $paged = $combinado->forPage($page, $perPage);

            $paginator = new LengthAwarePaginator(
                $paged,
                $combinado->count(),
                $perPage,
                $page,
                ['path' => url()->current(), 'query' => request()->query()]
            );

            return response()->json($paginator);
        

    }

    // FORMACION: Métodos necesarios

    public function bloques()
    {
        return $this->hasMany(CursoBloque::class)->orderBy('orden');
    }    
    /**
    * Obtener los tutores asignados al curso.
    */

    public function cursos_tutores()
    {
        return $this->hasMany(CursoTutor::class);
    }

    /* 3 Ways - Alexis Bogado */

    // Obtener los patrocinadores asignados al curso
    public function patrocinadores() {
        return $this->belongsToMany(Organizacion::class, 'cursos_patrocinadores');
    }

    // Obtener los organizadores asignados al curso
    public function organizadores() {
        return $this->belongsToMany(Organizacion::class, 'cursos_organizadores');
    }

    // Obtener los avaladores asignados al curso
    public function avaladores() {
        return $this->belongsToMany(Organizacion::class, 'cursos_avaladores');
    }

    // Obtener los acreditadores asignados al curso
    public function acreditadores() {
        return $this->belongsToMany(Organizacion::class, 'cursos_acreditadores');
    }

    // Comprobar configuración del curso
    public function configuracion($id) {
        return ($this->belongsToMany(CursoConfiguracion::class, 'cursos_configuraciones_pivot')->wherePivot('curso_configuracion_id', $id)->count() > 0);
    }

    // Diploma del curso
    public function diploma() {
        return $this->belongsTo(Diploma::class, 'diploma_id');
    }

    // Encuesta del curso
    public function encuesta() {
        return $this->belongsTo(Encuesta::class, 'encuesta_id');
    }

    // Promoción del curso
    public function promociones() {
        return $this->hasMany(CursoPromocion::class, 'curso_id');
    }

    /*	Estados del curso:
        PENDIENTE DE LANZAMIENTO		La fecha de inicio es 0000-00-00 o el archivo no está asociado.
        PRÓXIMA CONVOCATORIA			La fecha actual es anterior a la de inicio (y de matriculación en caso de existir).
        MATRÍCULA ABIERTA				La fecha actual está entre la de matrícula y la de inicio.
        CURSO ACTIVO					La fecha actual está entre la de inicio y la de fin.
        CURSO EXCLUSIVO PARA SOCIOS		Curso activo, donde el precio para socios es cero, pero no el de no socios.
        CURSO ABIERTO					Curso activo, donde el precio para socios y no socios es cero.
        CURSO CERRADO 					La fecha actual es posterior a la de fin.
    */

    public function getDescripcionEstadoAttribute() {
        $current_date = date("Y-m-d H:i:s");
        $result = "";

        // if ($this->estado == 0):
        //     $result = "Pendiente de lanzamiento";
        // elseif ($this->configuracion(5)):
        //     $result = "Exclusivo para socios";
        // endif;
        
        if ($this->fecha_inicio > 0 || (!$this->fecha_inicio && $this->estado == 1)):
            if ($this->fecha_fin > 0):
                if ($current_date >= $this->fecha_inicio && $current_date < date('Y-m-d', strtotime($this->fecha_fin.' + 1 days'))):
                    if ($this->precio_socio == 0):
                        if ($this->precio_nosocio == 0):
                            $result .= "Abierto";
                        else:
                            $result .= "Exclusivo para socios";
                        endif;
                    else:
                        $result .= "Activo";
                    endif;
                elseif ($current_date < $this->fecha_inicio):
                    if ($this->fecha_matriculacion_inicio > 0 && $current_date >= $this->fecha_matriculacion_inicio):
                        $result .= "Matrícula abierta";
                    else:
                        $result .= "Pendiente de lanzamiento";
                    endif;
                elseif ($current_date >= date('Y-m-d', strtotime($this->fecha_fin.' + 1 days'))):
                    $result .= "Cerrado";
                endif;
            else:
                if ($current_date >= $this->fecha_inicio):
                    $result .= "Activo";
                elseif ($current_date < $this->fecha_inicio):
                    if ($this->fecha_matriculacion_inicio > 0 && $current_date >= $this->fecha_matriculacion_inicio):
                        $result .= "Matrícula abierta";
                    else:
                        $result .= "Próxima convocatoria";
                    endif;
                endif;
            endif;
        else:
            $result .= "Pendiente de lanzamiento";
        endif;
    

        return $result;
    }

    /**
     * Obtener configuracion de curso.
     * Si es exclusivo para socios o no
     */
    public function getConfiguracionSociosAttribute() {
        if ($this->configuracion(5)):
            return true;
        else:
            return false;
        endif;
    }
    
    /**
     * Obtener las autoevaluaciones que tiene un curso
     * 
     * @return array
     */
    public function autoevaluaciones() {
        $bloque_Ids = array_map(function($bloque) { return $bloque["id"]; }, $this->bloques->toArray());
        return Cuestionario::whereNull('deleted_at')
                            ->whereIn('bloque_id', $bloque_Ids)
                            ->where(function ($query) {
                                $query->where('id_superior', '<=', 0)
                                      ->orWhereNull('id_superior');
                            })->get();
    }

    // Obtener los items que tiene el curso
    public function items_totales() {
        $bloque_Ids = array_map(function($bloque) { return $bloque["id"]; }, $this->bloques->toArray());
        return BloqueItem::whereNull('deleted_at')->whereIn('bloque_id', $bloque_Ids)->get();
    }

    // Obtener el bloque anterior
    public function bloque_anterior($bloque) {
        $index = 0;
        foreach ($this->bloques->toArray() as $key => $value):
            if ($bloque->id == $value["id"]) $index = $key;
        endforeach;
        
        return $this->bloques()->get()[$index - 1] ?? $this->bloques()->get()[$index];
    }

    // Comprobar si el registro está abierto
    public function registroAbierto() {
        $now = time();

        if ($this->fecha_matriculacion_inicio && strtotime($this->fecha_matriculacion_inicio) >= $now) return false;
        else if ($this->fecha_matriculacion_fin && $now >= strtotime($this->fecha_matriculacion_fin)) return false;
        else if ($this->fecha_fin && $now >= strtotime(date('Y-m-d', strtotime($this->fecha_fin.' + 1 days')))) return false;

        return true;
    }

    /* End */

    // 3 Ways - Carlos Colmenarez
    /**
    * Días restantes para finalizar el curso por el usuario
    */

    public function diasRestantes($fecha_registro, $periodo)
    {
        $fecha_fin=date("d-m-Y", strtotime($fecha_registro. "+". $periodo. " days"));
        $hoy= new DateTime();
        $fecha_fin= new DateTime($fecha_fin);

        $resta = $fecha_fin->diff($hoy);

        if ($hoy > $fecha_fin) {
            $dias_restantes = 0;
        } else {
            $dias_restantes = $resta->days + 1;
        }
        
        return $dias_restantes;
    }

    /****
     * A3 Ways - Alex R.
     * Saber si hay plazas en los expedientes del curso
     */
    public function hay_plazas() {
        $expedientes = CursoExpediente::where('curso_id', $this->id)->get();
        $hayPlazas = false;
        // Si no hay ningun expediente asignado al curso, no poner limite de plazas de registro
        if($expedientes->count() == 0){
            $hayPlazas = true;
        }else{
            foreach ($expedientes as $expediente){
                if($expediente->hay_plazas()){
                    $hayPlazas = true;
                    break;
                }
            }
        }

        return $hayPlazas;
    }

    
    /****
     * A3 Ways - Alex R.
     * Obtener curso_expediente_id del curso del cual el alumno va a hacer uso.
     */
    public function expedientePlazaLibre() {
        $curso_expediente_id = null;
        if($this->hay_plazas()){
            $expedientes = CursoExpediente::where('curso_id', $this->id)->get();
            if($expedientes->count() != 0){
                foreach ($expedientes as $expediente){
                    if($expediente->hay_plazas()){
                        $curso_expediente_id = $expediente->id;
                        break;
                    }
                }
            }
        }
        
        return $curso_expediente_id;
    }

    /**
     * 3ways Euro Fuenmayor
     * Devuelve si un curso es estricto
     * @return bool|null
     */
    public function getIsStrictAttribute()
    {
        $is_strict = null;
        $course_configuration_strict_id = (CursoConfiguracion::where('titulo', 'CURSO ESTRICTO.')->first())->id;
        if($course_configuration_strict_id) {
            $is_strict = $this->configuracion($course_configuration_strict_id);
        }
        return $is_strict;
    }

    public function cursos_clave_unica() {
        return $this->hasMany(CursosClaveUnica::class, 'curso_id', 'id');
    }

    public function proyectoFormativo()
    {
        return $this->belongsTo(ProyectoFormativo::class);
    }
}
