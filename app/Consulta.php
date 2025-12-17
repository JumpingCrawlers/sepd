<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
// para la query directa a países
use DB;
// para el formateo de fechas en español
use Jenssegers\Date\Date;


class Consulta extends Model
{
    //
    protected $table = 'excelencia';
    public $timestamps = false;
    // para marcar las instancias de Carbon
    protected $dates = [
        'fecha_reg'
    ];
    // añadir la descripción del área de gestión
    protected $appends = ['descripcion_area_gestion', 'fecha_formateada'];

    /***************************************************
     * SCOPES
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

        static::addGlobalScope('visibles', function (Builder $builder) {
            $builder->where('publicado', 1)
                    ->orderBy('fecha_reg', 'desc');
        });
        
        static::creating(function ($query) {
            $query->fecha_reg = date('Y-m-d');
        });
    }

    /***************************************************
     * Atributos calculados!
     *
     ***************************************************/

    /**
     * Descripcion del área de gestión
     * 
     * @return string
     */
    public function getDescripcionAreaGestionAttribute()
    {
        $desc = "";

        switch($this->area_gestion) {
            case "investigacion":
                $desc = "Investigación";
                break;
            case "clinica":
                $desc = "Gestión Clínica";
                break;
            case "calidad":
                $desc = "Calidad";
                break;
        }
        
        return $desc;
    }

    /**
     * Fecha de la consulta
     * 
     * @return string
     */
    public function getFechaFormateadaAttribute()
    {
        return $this->formatea_fecha($this->fecha_reg);
    }

    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/

    /**
     * Lista de proyectos filtrados según parámetros del request (API)
     * @return collection Proyecto
     */
    public static function filtrados($request) {

        // Montamos la query sin filtros
        $query = Consulta::query();
        
        // Recorrer la lista de parámetros y montar la query
        $gestion = array();

        foreach ($request->all() as $filtro => $valor) {
            // los parámetros tienen el id en el nombre -> recuperarlo
            $variable = preg_split('/_/',$filtro);
            switch (strstr($filtro, "_", true)) {
                case "texto":
                    $query = $query->where(function ($query) use ($valor) {
                                    $query->where('titulo', 'like', '%' . $valor . '%')
                                          ->orWhere('consulta', 'like', '%' . $valor . '%')
                                          ->orWhere('respuesta', 'like', '%' . $valor . '%');
                             });
                    break;
                case "areagestion":
                    $gestion[] = $variable[1];
                    break;
                default:
                    // page/tipo -> nada
                    break;
         
            }
        }
        
        // Montar el resto de la query
        if (!empty($gestion)) {
            $lista_gestion = implode("','",$gestion);
            $query = $query->whereRaw("area_gestion IN ('".$lista_gestion."')");
        }

        return $query->paginate(setting('site.elementos_pagina'));

    }

}
