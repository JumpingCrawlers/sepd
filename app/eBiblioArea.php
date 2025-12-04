<?php

namespace App;

class eBiblioArea extends Model
{
    //
    protected $table = 'areas';

    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/

    /**
     * Lista de área para filtro
     * 
     * @return collection de areas
     */
    public static function filtro() {
        
        // Alias para las columnas (homogeneizar para plantilla de filtro)
        return self::selectRaw('id as id_area, id, nombre as area, nombre')
                ->where('presentacion', '=', 0)
                ->orderBy('id')
                ->get();

    }

}
