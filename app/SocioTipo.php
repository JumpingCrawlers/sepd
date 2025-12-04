<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocioTipo extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'socios_tipos';
    
    /***************************************************
     *                                                 *
     *     Funciones dedicadas, normalmente static     *
     *                                                 *
     ***************************************************/

    /**
     * Obtener la cuota de un tipo de socio
     * 
     * @param string $name Nombre del tipo de socio
     * 
     * @return double
     */
    public static function getSociedadCuotaByName($name) {
        return (static::where('name', $name)->pluck('cuota')->first() ?? 0.00);
    }

    /**
     * Obtener sociedades internacionales
     * 
     * @return SocioTipo[]
     */
    public static function getSociedadesInternacionales() {
        return static::where('internacional', 1)->get();
    }
}
