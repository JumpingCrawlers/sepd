<?php

namespace App;

use TCG\Voyager\Facades\Voyager;

class Setting extends Model
{

    protected $appends = ['src'];
    
    /*****************************************************************
     * ACCESSORS: Atributos calculados
     * 
     *
     *****************************************************************/

    /**
     * Devuelve el src del icono
     * @return string
     */
    public function getSrcAttribute() {

        return Voyager::image($this->value);

    }

    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/

    /**
     * Lista de iconos!!
     * 
     * @return collection of iconos
     */
    public static function iconos() {
        
        return self::where('group', 'Iconos')->get();

    }

    
}
