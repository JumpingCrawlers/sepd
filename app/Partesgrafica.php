<?php

namespace App;

use App\Role;

class Partesgrafica extends Model
{
    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/

    /**
     * Redes sociales
     * @return collection Redes sociales
     */
    public static function redes_sociales() {

        $rol_institucional = Role::rolInstitucional();
        
        return static::where('tipo','=','rrss')
                ->where('role_id', '=', $rol_institucional->id)
                ->get();

    }
    
    /**
     * Relación con pastillas. Necesario para el preview
     * LISTA DE PIVOT COLUMNS IGUAL QUE EN PASTILLA!!!!
     * @return Pastilla con pivot
     */
    public function pastillas() {
        
        $pivot_columns = [
            'texto',
            'texto_cabecera',
            'texto_boton',
            'posicion_elementos',
            'posicion_boton',
            'enlace',
            'destino_enlace'
        ];
        return $this->belongsToMany('App\Pastilla')->withPivot($pivot_columns);
    }
    
    /**
     * Parte PIVOT pastillas-partes graficas vacía. Necesario para el preview
     * @return Pivot
     */
    public static function pivot_pastillas_vacio() {
        
        $pivot_array = [
            'texto' => null,
            'texto_cabecera' => null,
            'texto_boton' => null,
            'posicion_elementos' => null,
            'posicion_boton' => null,
            'enlace' => null,
            'destino_enlace' => null,
        ];
        return (object) $pivot_array;
    }
    
    /**
     * Relación con sliders. Necesario para el preview
     * LISTA DE PIVOT COLUMNS IGUAL QUE EN SLIDER!!!!
     * @return Slider con pivot
     */
    public function sliders() {
        
        $pivot_columns = [
            'titulo',
            'texto',
            'texto_align',
            'texto_color',
            'boton_texto',
            'boton_color',
            'boton_bgcolor',
            'posicion_elementos',
            'posicion_boton',
            'enlace',
            'destino_enlace'
        ];
        return $this->belongsToMany('App\Slider')->withPivot($pivot_columns);
    }

    /**
     * Parte PIVOT pastillas-partes graficas vacía. Necesario para el preview
     * @return Pivot
     */
    public static function pivot_sliders_vacio() {
        
        $pivot_array = [
            'titulo' => null,
            'texto' => null,
            'texto_align' => null,
            'texto_color' => null,
            'boton_texto' => null,
            'boton_color' => null,
            'boton_bgcolor' => null,
            'posicion_elementos' => null,
            'posicion_boton' => null,
            'enlace' => null,
            'destino_enlace' => null
        ];
        return (object) $pivot_array;
    }

}
