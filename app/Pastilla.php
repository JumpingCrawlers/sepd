<?php

namespace App;


class Pastilla extends Model
{
    protected $with = ['partesgraficas', 'video', 'sepdtv'];

    /***************************************
     * WITH ..... relaciones de pastilla
     *
     **************************************/

    /**
     * Datos de la/s imagen/es de la pastilla
     * LISTA DE PIVOT COLUMNS IGUAL QUE EN PARTESGRAFICA!!!!
     * @return PartesGrafica
     */
    public function partesgraficas() {
        
        $pivot_columns = [
            'texto',
            'texto_cabecera',
            'caja',
            'caja_color',
            'caja_opacidad',
            'texto_sombra',
            'texto_sombra_inversa',
            'texto_boton',
            'posicion_elementos',
            'posicion_boton',
            'enlace',
            'destino_enlace',
            'external'
        ];

        return $this->belongsToMany('App\Partesgrafica')->withPivot($pivot_columns);
    }
    
    /**
     * Datos del vídeo relacionado
     * @return Video
     */
    public function video() {
        
        return $this->belongsTo('App\Video');
    }
    
    /**
     * Datos del vídeo SEPDTV relacionado
     * @return Tv
     */
    public function sepdtv() {
        
        return $this->belongsTo('App\Sepdtv', 'sepdtv_id', 'codigo');
    }
}
