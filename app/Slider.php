<?php

namespace App;

class Slider extends Model
{
    protected $with = ['partesgraficas'];

    /***************************************
     * WITH ..... relaciones de página
     *
     **************************************/

    /**
     * Datos de las diapositivas del slider
     * @return PartesGrafica
     */
    public function partesgraficas() {
        
        $pivot_columns = [
            'titulo',
            'texto',
            'texto_align',
            'texto_color',
            'caja',
            'caja_color',
            'caja_opacidad',
            'texto_sombra',
            'texto_sombra_inversa',
            'boton_texto',
            'boton_color',
            'boton_bgcolor',
            'posicion_elementos',
            'posicion_boton',
            'enlace',
            'destino_enlace',
            'external',
        ];
        return $this->belongsToMany('App\Partesgrafica')->withPivot($pivot_columns)->orderBy('partesgrafica_slider.orden');
    }

}
