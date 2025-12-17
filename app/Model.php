<?php

namespace App;

/* Modelo general base de todos los modelos */

use Illuminate\Database\Eloquent\Model as Eloquent;
// para el formateo de fechas en español
use Jenssegers\Date\Date;

class Model extends Eloquent
{
    protected $guarded = [];
    
    /* Formatear una fecha en español
     * 
     * @param fecha -> fecha a formatear
     * @param formato -> por defecto j F Y (carbon)
     * @returns string
     */
    public static function formatea_fecha($fecha, $formato = 'j F Y') {

        Date::setLocale('es');
        
        $objeto = new Date($fecha);
        return ucfirst($objeto->format($formato));

    }
}