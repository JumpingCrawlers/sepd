<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
// para el formateo de fechas en español
use Jenssegers\Date\Date;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

// Para la biblioteca, para fusionar el resultado con Cursos, es necesario usar Collections
use Illuminate\Support\Collection;

class Documento extends Model
{
    use SoftDeletes;
    
    protected $table = 'elearning__biblio_documento';
    public $primaryKey = 'id_documento';
    public $timestamps = false;
    // para marcar las instancias de Carbon
    
    protected $dates = [
        'fecha_reg',
        'fecha'
    ];
    // Accessors:
    protected $appends = ['fecha_formateada'];

    /***************************************************
     * GLOBAL SCOPE
     *
     ***************************************************/

   /**
    * Boot para el model -> GlobalScope.
    *
    * @return void
    */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('biblioteca', function (Builder $builder) {
            $builder->orderBy('fecha', 'desc')->orderBy('titulo', 'asc');
        });
    }

    /*****************************************************************
     * ACCESSORS: Atributos calculados
     * 
     *
     *****************************************************************/

    /**
     * Devuelve la fecha del dossier formateada en español
     * @return string
     */
    public function getFechaFormateadaAttribute() {
        
        Date::setLocale('es');
        
        $fecha = new Date($this->fecha);
        $fecha_txt = ucfirst($fecha->format('j F Y'));
        
        return $fecha_txt;
    }

    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/

    /**
     * Documentos que pertenecen a un área determinada
     * 
     * @param array|int|string $areas
     * @return array de documentos
     */
    public function scopeAreas($query, array|int|string  $areas)
    {
        $areas = is_array($areas) ? $areas : [$areas];

        return $query->whereIn('elearning__biblio_documento.id_area', $areas);
    }

    /**
     * Documentos que pertenecen a un área determinada
     * 
     * @param array|int|string $area
     * @return array de documentos
     */
    public static function porArea(array|int|string $areas)
    {
        $areas = is_array($areas) ? $areas : [$areas];
        // biblio_documento JOIN biblio_area_relacion
        $documentos = self::whereIn('elearning__biblio_documento.id_area', $areas)->paginate(setting('site.elementos_pagina'));

        return $documentos;
    }
    
    /**
     * Documentos de un formato determinado
     * 
     * @param int $formato
     * @return array de documentos
     */
    public static function porFormato($formato) {
        
        // biblio_documento JOIN biblio_area_relacion
        return self::where('id_formato', '=', $formato)->paginate(setting('site.elementos_pagina'));

    }

    /**
     * Diferentes formatos de documentos de la biblioteca
     * @return collection (formatos)
     */
    public static function formatos() {

        // alias de las columnas para homogeneizar filtros
        return self::join('elearning__biblio_formato', 'elearning__biblio_formato.id_formato', '=', 'elearning__biblio_documento.id_formato')
                    ->where('elearning__biblio_documento.id_formato', '<>', 10)
                    ->where('elearning__biblio_documento.titulo', '<>', '')
                    ->where(function ($query){
                        $query->where('elearning__biblio_documento.enlace', '<>', '')
                              ->orWhere('elearning__biblio_documento.archivo_biblio', '<>', '');
                    })
                    ->selectRaw('DISTINCT elearning__biblio_formato.id_formato as id, elearning__biblio_formato.formato as nombre')
                    ->orderBy('id', 'asc')
                    ->get();

    }

    /**
     * Diferentes emedia de la biblioteca
     * @return collection (emedia)
     */
    public static function emedia() {

        // Se quiere mantener la agrupación actual: 
        // - Multimedia (formato 10)
        // - Videoteca (formato webcast)
        $emedia = [];
        $emedia[] = array("id" => 10, "nombre" => "Multimedia");
        $emedia[] = array("id" => "webcast", "nombre" => "Videoteca SEPD");
        
        return json_decode(json_encode((object) $emedia), FALSE);
    }

    /**
     * Diferentes años para los que existe un documento de la biblioteca
     * @return collection (años)
     */
    public static function anyos() {

        // con alias de las columnas para homogeneizar filtros
        return self::withoutGlobalScopes()
                ->where('titulo', '<>', '')
                ->where(function ($query) {
                    $query->where('enlace', '<>', '')
                          ->orWhere('archivo_biblio', '<>', '');
                })
                ->selectRaw('DISTINCT YEAR(fecha) as id, YEAR(fecha) as nombre')
                ->orderBy('id', 'desc')
                ->get();

    }

    /**
     * Lista de la biblioteca filtrada según parámetros del request (API)
     * @return collection Biblioteca
     */
    public static function  filtrados($request) {

        // Se debe recoger información tanto de DOCUMENTOS como de CURSOS
        // NO SE RECUPERA NADA DE CURSOS SI:
        //  a. no está marcada la opción Videoteca SEPD (emedia_webcast), y
        //  b. hay seleccionado algún formato

        // Determinar qué tipos de elementos hay que recuperar
        $hayDocs = false;
        $hayCursos = false;

        foreach ($request->all() as $filtro => $valor) {
            $partesFiltro = preg_split('/_/',$filtro);
            // si es formato (los dos de emedia también son formatos en realidad)
            if ($partesFiltro[0] == 'formato' || $partesFiltro[0] == 'emedia') {
                ($partesFiltro[1] == "webcast") ? $hayCursos = true : $hayDocs = true;
            }
        }
        // si no hay ni uno ni otro => los dos
        if (!$hayCursos && !$hayDocs) {
            $hayCursos = true;
            $hayDocs = true;
        }

        // Recuperar los filtros a aplicar
        $idArea = 0;
        $termino = '';
        $formatos = array();
        $anyos = array();
        $areas = array();
        
        // Para ello, hay que comprobar el area (Trabajos -> Recuperar el área si había)
        if ($request->area) {
            // id de área (incluye Trabajos Investigacion, que es un formato)
            $idArea = Documento::getAreaId($request->area);
        }
        // Recorrer el resto de parámetros
        // Se guardan separados para montar los OR posteriormente
        foreach ($request->all() as $filtro => $valor) {
            // los parámetros tienen el id en el nombre -> recuperarlo
            $partesFiltro = preg_split('/_/',$filtro);
            switch ($partesFiltro[0]) {
                case "texto":
                    $termino = $valor;
                    break;
                case "formato":
                    $formatos[] = $partesFiltro[1];
                    break;
                case "anyos":
                    $anyos[] = $partesFiltro[1];
                    break;
                case "areas":
                    $areas[] = $partesFiltro[1];
                    break;
                case "emedia":
                    // solo si no es "webcast", y se mete en formatos, ya que en realidad Multimedia es un formato
                    ($partesFiltro[1] == 'webcast') ?: $formatos[] = $partesFiltro[1];
                    break;
                default:
                    // "page" -> nada
                    break;
            }
        }

        // Montar las listas de elementos para filtrar con IN ( , , , )
        if (!empty($formatos)) {
            $lista_formatos = implode("','",$formatos);
        }
        if (!empty($anyos)) {
            $lista_anyos = implode("','",$anyos);
        }
        if (!empty($areas)) {
            $lista_area = $areas;
        }
        // Recuperar los registros de CURSOS y/o DOCUMENTOS
        $coleccionCursos = new Collection;
        $coleccionDocs = new Collection;

        // Para los cursos
        if ($hayCursos) {
            // iniciar la query según la antigua consulta de formacion/bibliotecas.php
            // Sin scope, pero seleccionando solo las columnas necesarias y con alias
            $query = Curso::withoutGlobalScopes()->paraBiblioteca();
            $query = $query->where('estado', '1')
                        //    ->where('acceso_clave', '0')
                           ->where('precio_socio', '0')
                           ->where('precio_nosocio', '0')
                           ->where(function ($query) {
                                    $query->where('fecha_fin', '>=', 'CURDATE()')
                                          ->WhereNull('fecha_fin');
                           });
            // Resto de filtros
            // ($idArea > 0 ) ? $query = $query->where("id_area", $idArea) : null;
            (isset($lista_anyos)) ? $query = $query->whereRaw("YEAR(fecha_inicio) IN ('".$lista_anyos."')") : null;
            // el filtro de texto
            $query = $query->where(
                    function ($query) use ($termino) {
                        $query->where('titulo', 'like', '%' . $termino . '%')
                              ->orWhere('etiquetas', 'like', '%' . $termino . '%')
                              ->orWhere('descripcion', 'like', '%' . $termino . '%');
            });
            $coleccionCursos = $query->get()->makeHidden(['descripcion_estado', 'fechas_formateadas']);
        }

        // Para los documentos
        if ($hayDocs) {
            // Primero el area: si hay area, hay un JOIN
            if ($idArea > 0) {
                // comprobar que no es Trabajos Investigacion SEPD (15)
                if ($idArea != 15) {
                    $query = self::where('elearning__biblio_documento.id_area', $idArea);      
                } else {
                    $query = Documento::query()->where('id_formato', '=', $idArea);   
                }
            } else {
                // query vacía
                $query = Documento::query();
            }

            // Resto de filtros
            (isset($lista_formatos)) ? $query = $query->whereRaw("id_formato IN ('".$lista_formatos."')") : null;
            (isset($lista_anyos)) ? $query = $query->whereRaw("YEAR(fecha) IN ('".$lista_anyos."')") : null;
            (isset($lista_area)) ? $query = $query->whereIn('elearning__biblio_documento.id_area', $lista_area) : null;
            // el filtro de texto
            $query = $query
                    ->where(function ($query) {
                        $query->where('tipo', '!=', 'pdf')->orWhereNotNull('archivo_biblio');
                    })
                    ->where(function ($query)  {
                        $query->where('enlace', '!=', null)
                            ->orWhere('archivo_biblio', '!=', null);
                    })
                    ->where(function ($query) use ($termino) {
                        $query->where('titulo', 'like', '%' . $termino . '%')
                              ->orWhere('etiquetas', 'like', '%' . $termino . '%')
                              ->orWhere('descripcion', 'like', '%' . $termino . '%');
                    });
            
            $coleccionDocs = $query->get()->makeHidden(['id_formato', 'id_area', 'etiquetas']);
        }
        
        /*
        unir los arrays ordenados por fecha
        $resultados = array_merge($coleccionDocs, $coleccionCursos);
        usort($resultados, function($a,$b) {
            return $a['fecha'] <=> $b['fecha'];
        });
        */

        $resultados = $coleccionCursos->concat($coleccionDocs)->sortByDesc('fecha');
        
        return $resultados->paginate(setting('site.elementos_pagina'));

    }

    // Función para recoger el id del area
    public static function getAreaId($area) {
        switch ($area) {
            case 'eii':
                return 1;
                break;
            case 'tdsmh':
                return 2;
                break;
            case 'idc':
                return 3;
                break;
            case 'vbp':
                return 4;
                break;
            case 'ee':
                return 5;
                break;
            case 'h':
                return 6;
                break;
            case 'gen':
                return 10;
                break;
            case 'ct':
                return 11;
                break;
            case 'tis':
                return 15; // no es un área sino un formato
                break;
            default:
                return undefined;
                break;
        }
    }
}
