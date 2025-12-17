<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
// para el formateo de fechas en español
use Jenssegers\Date\Date;
//para permitir ver enlaces si esta logueado
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Revista extends Model
{
    //
    protected $table = 'publicaciones';
    public $timestamps = false;
    // para marcar las instancias de Carbon
    protected $dates = ['fecha'];
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

         static::addGlobalScope('publicaciones', function (Builder $builder) {
             $builder->orderBy('fecha_reg', 'desc');
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
        
        $fecha = new Date($this->fecha_reg);
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
                ->selectRaw('DISTINCT year as id, year as nombre')
                ->orderBy('id', 'desc')
                ->get();

    }


    /**
     * Lista de revistas filtradas según parámetros del request
     * @return collection Revista
     */
    public static function filtrados($request, $usuario = null) {

        // Montamos la query sin filtros
        $query = Revista::query();
        
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
            $query = $query->whereRaw("year IN ('".$lista_anyos."')");
        } elseif ($request->texto_busqueda == '') {
            // si no hay años, el último año!
            $query = $query->whereRaw("year > YEAR(NOW())-3");
        }
        
        $query = $query->whereRaw(" id_revista = '".$request->tipo."'")
                       ->orderBy('year', 'desc')
                       ->orderBy('numero', 'desc');

        // si el usuario está registrado o SEPD Gastronews (revista 3), que es de acceso libre
        $socio = UsuarioSocio::where("usuario_id", $usuario->id)->whereNull('deleted_at')->first();

        if ($socio || $request->tipo == 3) {
            return $query->paginate(setting('site.elementos_pagina'));
        } else {
            return $query->select('id','id_revista', 'descripcion', 'portada','year', 'numero', 'fecha_reg')->paginate(setting('site.elementos_pagina'));
        }

    }

}
