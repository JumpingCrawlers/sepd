<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
// para el formateo de fechas en español
use Jenssegers\Date\Date;

class Noticia extends Model
{
    //
    protected $table = 'noticias';
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

        static::addGlobalScope('noticias', function (Builder $builder) {
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
     * Noticias de una seccion determinada
     * 
     * @param string $seccion
     * @return collection de noticias
     */
    public static function porSeccion($seccion) {
        
        return self::where('seccion', '=', $seccion)->paginate(setting('site.elementos_pagina'));

    }

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
     * Lista de noticias filtradas según parámetros del request
     * @return collection Noticias
     */
    public static function filtrados($request) {

        // Montamos la query sin filtros
        $query = Noticia::query();
        
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

        return $query->paginate(setting('site.elementos_pagina'));

    }

    public static function ultimasNoticias () {
        /**
         * 3 ways - Euro Fuenmayor
         * Ajustado obtener las noticias de los ultimos 30 dias
         * Si la cantidad de noticas de las ultimos 30 dias es menor a 10, entonces toma las ultimas 10 noticias (anterior: eran las ultimas 30 noticias)
         */
        $fecha_mes_atras = date('Y-m-d h:g:s', strtotime('-30 days'));
        $noticias = self::where('publico', 1)->where('fecha', '>=', $fecha_mes_atras)->get();
        if($noticias->count() < 10){
            $noticias = self::where('publico', 1)->orderBY('fecha', 'DESC')->limit(10)->get();
        }
        return  $noticias;
    }

    /**
     * Scope para obtener las últimas noticias
     * 
     * @autor @carlos.anselmi <anselmi@infinety>
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastNew (Builder $query): Builder
    {   
        return $query
            ->publico()
            ->orderFechaDesc()
            ->limit(10);
    }

    /**
     * 
     * @autor @carlos.anselmi <anselmi@infinety>
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderFechaDesc(Builder $query): Builder
    {
        return $query->orderBy('fecha', 'desc');
    }

    /**
     * 
     * @autor @carlos.anselmi <anselmi@infinety>
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublico(Builder $query): Builder
    {
        return $query->where('publico', 1);
    }
}
