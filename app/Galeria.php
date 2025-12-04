<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
// para el formateo de fechas en español
use Jenssegers\Date\Date;
use DB;

class Galeria extends Model
{
    //
    protected $table = 'imagenes';
    public $timestamps = false;
    // para marcar las instancias de Carbon
    protected $dates = [
        'fecha',
        'fecha_reg'
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

        static::addGlobalScope('galeria_desc', function (Builder $builder) {
            $builder->orderBy('fecha', 'desc');
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
    public function getFechaFormateadaAttribute()
    {

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
     * Diferentes años para los que existe un dossier
     * @return collection Años
     */
    public static function anyos()
    {

        // alias de las columnas para homogeneizar filtros
        return self::withoutGlobalScopes()
            ->selectRaw('DISTINCT YEAR(fecha) as id, YEAR(fecha) as nombre')
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Función para comprobar si existe la carpeta pasada por parámetro (Solo del nivel uno)
     * @return número de columnas
     */
    public static function existeCarpeta($carpeta1)
    {
        $query = Galeria::query()
            ->where('carpeta1', $carpeta1)->whereNull('deleted_at');
        $resultados = $query->get();

        return $resultados->count();
    }

    /**
     * Función para recoger el nombre de la carpeta pasada por parametro
     * @return string nombre
     */
    public static function getNombreCarpeta($carpeta)
    {
        $query =  DB::table('c_imagenes')
            ->selectRaw('nombre')
            ->where('id', $carpeta->carpeta1);
        return $query->get();
    }
    /**
     * Lista de la galeria filtrada según parámetros del request
     * @return collection Galeria
     */
    public static function filtrados($request)
    {

        // se recogen las carpetas que se pasan por el $request
        $carpeta1 = $request->carpeta1;
        $carpeta2 = $request->carpeta2;
        $carpeta3 = $request->carpeta3;

        // Montamos la query recogiendo todas las carpetas de nivel uno
        $query = self::join('c_imagenes', 'c_imagenes.id', '=', 'imagenes.carpeta1')
            ->selectRaw('carpeta1, nombre')
            ->orderBy('position','DESC')
            ->groupBy('carpeta1', 'nombre');

        // Si se pasan las 3 carpetas montamos la query para sacar todas las imagenes que coincidan
        if ($carpeta1 != null && $carpeta2 != null && $carpeta3 != null) {
            //carpeta3
            $query = Galeria::query()
                ->where('carpeta1', 'like', $carpeta1)
                ->where('carpeta2', 'like', $carpeta2)
                ->where('carpeta3', 'like', $carpeta3)->whereNull('deleted_at');

            // Si se pasan las 2 carpetas montamos la query para saber si hay otro nivel de carpetas para mostrar. Si NO lo hay se sacan las imágenes correspondientes
        } else if ($carpeta1 != null && $carpeta2 != null) {
            //capreta2
            $query = self::join('c_imagenes', 'c_imagenes.id', '=', 'imagenes.carpeta3')
                ->selectRaw('carpeta3, nombre')
                ->groupBy('carpeta3', 'nombre')
                ->where('carpeta1', $carpeta1)
                ->where('carpeta2', $carpeta2)->whereNull('deleted_at');
            $resultados = $query->get();

            if ($resultados->count() == 0) {
                $query = Galeria::query()
                    ->where('carpeta1', $carpeta1)
                    ->where('carpeta2', $carpeta2)->whereNull('deleted_at');
            }
            // Si se pasa 1 carpeta montamos la query para saber si hay otro nivel de carpetas para mostrar. Si NO lo hay se sacan las imágenes correspondientes
        } else if ($carpeta1 != null) {
            //capreta1
            $query = self::join('c_imagenes', 'c_imagenes.id', '=', 'imagenes.carpeta2')
                ->selectRaw('carpeta2, nombre')
                ->groupBy('carpeta2', 'nombre')
                ->where('carpeta1', $carpeta1)->whereNull('deleted_at');
            $resultados = $query->get();

            if ($resultados->count() == 0) {
                $query = Galeria::query()
                    ->where('carpeta1', $carpeta1)
                    ->where('carpeta2', 0)->whereNull('deleted_at');
            }
        }

        //dd($query->toSql());
        //return $query->paginate(setting('site.elementos_pagina'));
        return $query->paginate(20);
    }
}
