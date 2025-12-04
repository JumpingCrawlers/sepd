<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
// para el formateo de fechas en español
use Jenssegers\Date\Date;

class Sepdtv extends Model
{
    //
    protected $table = 'tv';
    public $primaryKey = 'codigo';
    public $incrementing = false;
    public $timestamps = false;
    // para marcar las instancias de Carbon
    protected $dates = [
        'fecha_reg'
    ];

    // Accessors:
    protected $appends = ['fecha_formateada'];

    /***************************************************
     * Global SCOPES
     *
     ***************************************************/
    // Solo vídeos VISIBLES
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('visibles', function (Builder $builder) {
            $builder->where('visible', '=', 1);
        });
    }

    
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
     * Vídeos que pertenecen a un área determinada
     * 
     * @param int $area
     * @return array de vídeos
     */
    public static function porArea($area)
    {
        // biblio_documento JOIN biblio_area_relacion
        $videos = self::join('tv_areas', 'tv.codigo', '=', 'tv_areas.codigo')->where('tv_areas.id_area', '=', $area)->get();

        return $videos;
    }

    /**
     * Lista de notas de sepdtv filtrados según parámetros del request
     * @return collection Sepdtv
     */
    public static function filtrados($request)
    {
        $query = Sepdtv::query();

        $areas = array();

        foreach ($request->all() as $filtro => $valor) {
            // los parámetros tienen el id en el nombre -> recuperarlo
            $variable = preg_split('/_/',$filtro);
            $condicion = strstr($filtro, "_", true);
            switch ($condicion) {
                case "texto":
                    $query = $query->where(function ($query) use ($valor) {
                                    $query->where('titulo', 'like', '%' . $valor . '%')
                                          ->orWhere('subtitulo', 'like', '%' . $valor . '%')
                                          ->orWhere('descripcion', 'like', '%' . $valor . '%');
                             });
                    break;
                case "areas":
                    $areas[] = $variable[1];
                default: 
                    // "page" -> nada
                    break;
            }
        }
        
        // Si hay un área seleccionada se crea la query con left join de la tabla "tv_areas"
        if ($request->area_tv) {
            $area = Sepdtv::getArea($request->area_tv);
            $query = self::join('tv_areas', 'tv.codigo', '=', 'tv_areas.codigo')->where('tv_areas.id_area', '=', $area);        
        }
        
        if (count($areas)) {
            $query = self::join('tv_areas', 'tv.codigo', '=', 'tv_areas.codigo')->whereIn('tv_areas.id_area', $areas);      
        }

        // ordenar por fecha descendiente
        $query->orderBy('fecha_reg', 'DESC');

        $query->addSelect(\DB::raw("*, CONCAT(SUBSTRING_INDEX(descripcion, ' ', 20), '...') as descripcion_sort"));

        return $query->paginate(setting('site.elementos_pagina'));

    }

    // Función para recoger el id del area
    public static function getArea($area) {
            switch ($area) {
                case 'eii': //1
                    return 1;
                    break;
                case 'ee': //5
                     return 5;
                    break;
                case 'h': //6
                    return 6;
                    break;
                case 'idc': //3
                    return 3;
                    break;
                case 'tdsmh': //2
                    return 2;
                    break;
                case 'vbp': //4
                    return 4;
                    break;
                case 'esp': //12
                    return 12;
                    break;
                case 'gen': //10
                    return 10;
                    break;
                default:
                    return undefined;
                    break;
            }
    }
}
