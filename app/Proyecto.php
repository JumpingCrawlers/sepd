<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
// para la query directa a países
use DB;
// para el formateo de fechas en español
use Jenssegers\Date\Date;


class Proyecto extends Model
{
    protected $table = 'cid_proyecto';

    public $primaryKey = 'id_proyecto';

    public $timestamps = false;
    
    protected $dates = [
        'fecha_inicio',
        'fecha_fin',
        'fecha_reg',
        'fecha_mod'
    ];

    protected $appends = ['datos_fase', 'url_miniatura'];

    /**
     * Boot para el model -> GlobalScope.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('visibles', function (Builder $builder) {
            $builder->where('visibilidad', 1)
                    ->orderBy('fase', 'asc')
                    ->orderBy('titulo', 'asc');
        });
    }

    /***************************************
     * WITH ..... relaciones de página
     *
     **************************************/

    /**
     * Datos de los patrocinadores del proyecto
     * @return collection Patrocinador
     */
    public function patrocinadores() {
        return $this->belongsToMany('App\Organizacion', 'cid_patrocinios', 'id_proyecto', 'id_patrocinador');
    }
    public function areas()
    {
        return $this->belongsToMany(
            Area::class,
            'areaables',
            'proyecto_id_proyecto',
            'area_id',
            $this->primaryKey,
            'id'
        );
    }

    /***************************************************
     * Atributos calculados!
     *
     ***************************************************/

    /**
     * Datos de la fase: descripción y clase CSS
     * 
     * @return array
     */
    public function getDatosFaseAttribute()
    {
        $datosFase = array();

        switch($this->fase) {
            case 1:
                $datosFase['descripcion'] = 'Diseño';
                $datosFase['clase_css'] = 'bg-diseno';
                break;
            case 2:
                $datosFase['descripcion'] = 'Reclutamiento';
                $datosFase['clase_css'] = 'bg-reclutamiento';
                break;
            case 3:
                $datosFase['descripcion'] = 'Análisis';
                $datosFase['clase_css'] = 'bg-analisis';
                break;
            case 4:
                $datosFase['descripcion'] = 'Difusión';
                $datosFase['clase_css'] = 'bg-difusion';
                break;

            case 5:
                $datosFase['descripcion'] = 'Cerrado';
                $datosFase['clase_css'] = 'bg-cerrado';
                break;
        }
        
        return $datosFase;
    }

    /**
     * Url completa de la miniatura
     * Servidor antiguo o nuevo? Si contiene files/ es el antiguo
     * 
     * @return string
     */
    public function getUrlMiniaturaAttribute()
    {
        $url = (strpos($this->miniatura, 'files/') === false)
                ? '/storage/'.$this->miniatura
                : '/storage/cid/'.$this->miniatura;
        return $url;
        
    }

    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/

    /**
     * Proyectos de investigacion
     * @return collection Proyecto
     */
    public static function investigacion() {

        return static::where('area_gestion','investigacionSepd')
                       ->paginate(setting('site.elementos_pagina'));

    }
    
    /**
     * Proyectos de gestión clinica
     * @return collection Proyecto
     */
    public static function gestion_clinica() {

        return static::where('area_gestion','excelenciaGestion')
                       ->paginate(setting('site.elementos_pagina'));

    }

    /**
     * Proyectos Calidad
     * @return collection Proyecto
     */
    public static function calidad() {

        return static::where('area_gestion','excelenciaCalidad')
                       ->paginate(setting('site.elementos_pagina'));

    }

    /**
     * Lista de proyectos filtrados según parámetros del request (API)
     * @return collection Proyecto
     */
    public static function filtrados($request) {

        // Montamos la query inicial
        $query = Proyecto::query();
        
        // Recorrer la lista de parámetros y montar la query
        // Las areas, los formatos, los paises, los guardamos separados para montar los OR
        $areas = array();
        $fases = array();
        $gestion = array();
        $estados = array();

        foreach ($request->all() as $filtro => $valor) {
            // los parámetros tienen el id en el nombre -> recuperarlo
            $variable = preg_split('/_/',$filtro);
            switch (strstr($filtro, "_", true)) {
                case "texto":
                    $query = $query->where(function ($query) use ($valor) {
                        $query->where('titulo', 'like', '%' . $valor . '%')
                                ->orWhere('etiquetas', 'like', '%' . $valor . '%')
                                ->orWhere('resumen', 'like', '%' . $valor . '%')
                                ->orWhere('descripcion', 'like', '%' . $valor . '%');
                        });
                    break;
                case "areas":
                    $areas[] = $variable[1];
                    break;
                case "fases":
                    $fases[] = $variable[1];
                    break;
                case "areagestion":
                    $gestion[] = $variable[1];
                    break;
                case "estados":
                    $estados[] = $variable[1];
                    break;
                default:
                    break;
         
            }
        }

        $query->when(!empty($areas), function ($query) use ($areas) {
            $query->whereHas('areas', function ($query) use ($areas) {
                $query->whereIn('area_id', $areas);
            });
        });

        $query->when(!empty($fases), function ($query) use ($fases) {
            $lista_fases = implode("','",$fases);
            $query->whereRaw("fase IN ('".$lista_fases."')");
        });

        $query->when(!empty($gestion), function ($query) use ($gestion) {
            $lista_gestion = implode("','",$gestion);
            $query->whereRaw("area_gestion IN ('".$lista_gestion."')");
        });

        $query->when(!empty($estados), function ($query) use ($estados) {
            $lista_estados = implode("','",$estados);
            $query->whereRaw("estado IN ('".$lista_estados."')");
        });

        return $query->orderBy('estado')->with('patrocinadores')->paginate(200);

    }
}
