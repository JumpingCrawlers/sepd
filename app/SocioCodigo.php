<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocioCodigo extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'socios_codigos';
    
    /***************************************************
     *                                                 *
     *     Funciones dedicadas, normalmente static     *
     *                                                 *
     ***************************************************/
    
    /**
     * Comprobar si un código es válido
     * 
     * @param string $codigo Código promocional
     * 
     * @return string|bool
     */
    public static function isValid($codigo) {
        return (static::where([
            [ 'codigo', $codigo ],
            [ 'valido', 1 ]
        ])->pluck('codigo')->first() ?? false);
    }

    /**
     * Comprobar si hay que pagar con el código ingresado
     * 
     * @param string $codigo Código promocional
     * 
     * @return bool
     */
    public static function requirePayment($codigo) {
        return (static::where('codigo', $codigo)->pluck('pago')->first() == 1);
    }
}
