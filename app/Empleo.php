<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Date\Date;
// comprobar si está conectado
use Auth;
// para crear alias a la columna conectado
use DB;

class Empleo extends Model
{
    //
    protected $table = 'empleo';
    public $timestamps = false;
    // para marcar las instancias de Carbon
    protected $dates = [
        'fecha',
        'fecha_reg'
    ];
    
    // Sección para colores
    protected $seccion = 'institucional';
    
    // Accessors:
    protected $appends = ['fecha_formateada', 'texto_formateado', 'conectado'];

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

        ///////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////
        // ATENCIÓN !! Se deberá cambiar el campo destacado a visible en la BD y en todas partes
        ///////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////
        // Filtro de los últimos 3 meses y que sean visibles !!!
        static::addGlobalScope('empleo', function (Builder $builder) {
            $builder->where('fecha', '>', 'DATE_SUB(CURDATE(), INTERVAL 3 MONTH)')
                    ->where('destacado', '1')
                    ->orderBy('fecha', 'desc');
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
    
    /**
     * Devuelve el texto con los códigos de contenido traducidos
     * @return string
     */
    public function getTextoFormateadoAttribute() {

        return getHtmlContenido($this->texto, $this->seccion);

    }
    
    /**
     * Flag de usuario conectado
     * @return string
     */
    public function getConectadoAttribute() {

        return Auth::user() !== null;

    }
    

    /**
     * Lista de noticias filtradas según parámetros del request
     * @return collection Noticias
     */
    public static function filtrados($request) {

        // Montamos la query sin filtros
        $query = Empleo::query();
        
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
                default:
                    // "page" -> nada
                    break;
         
            }
        }
        
        // Montar el resto de la query con los años
        /*if (!empty($anyos)) {
            $lista_anyos = implode("','",$anyos);
            $query = $query->whereRaw("YEAR(fecha) IN ('".$lista_anyos."')");
        } elseif ($request->texto_busqueda == '') {
            // si no hay años, el último año!
            $query = $query->whereRaw("fecha >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");
        }*/

        return $query->paginate(setting('site.elementos_pagina'));

    }

    

}
