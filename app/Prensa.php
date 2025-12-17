<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
// para el formateo de fechas en español
use Jenssegers\Date\Date;

class Prensa extends Model
{
    //
    protected $table = 'prensa';
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

         static::addGlobalScope('prensa', function (Builder $builder) {
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
     * Diferentes años para los que existe un dossier
     * @return collection Años
     */
    public static function anyos() {

        // alias de las columnas para homogeneizar filtros
        return self::withoutGlobalScopes()
                ->selectRaw('DISTINCT YEAR(fecha) as id, YEAR(fecha) as nombre')
                ->orderBy('id', 'desc')
                ->get();

    }


    /**
     * Lista de notas de prensa filtrados según parámetros del request
     * @return collection Prensa
     */
    public static function filtrados($request) {

        // Montamos la query sin filtros
        $query = Prensa::query();
        
        // Recorrer la lista de parámetros y montar la query
        $anyos = array();

        foreach ($request->all() as $filtro => $valor) {
            // los parámetros tienen el id en el nombre -> recuperarlo
            $variable = preg_split('/_/',$filtro);
            switch (strstr($filtro, "_", true)) {
                case "texto":
                    $query = $query->where(function ($query) use ($valor) {
                                    $query->where('titulo', 'like', '%' . $valor . '%')
                                          ->orWhere('texto', 'like', '%' . $valor . '%');
                             });
                    break;
                case "anyos":
                    $anyos[] = $variable[1];
                    break;
                default:
                    // "page" -> nada
                    break;
         
            }
        }
        
        // Montar el resto de la query con los años
        if (!empty($anyos)) {
            $lista_anyos = implode("','",$anyos);
            $query = $query->whereRaw("YEAR(fecha) IN ('".$lista_anyos."')");
        } elseif ($request->texto_busqueda == '') {
            // si no hay años, el último año!
            $query = $query->whereRaw("fecha > DATE_ADD(NOW(), INTERVAL -3 YEAR)");
        }

//        dd($query->toSql());
        return $query->paginate(setting('site.elementos_pagina'));

    }

}
