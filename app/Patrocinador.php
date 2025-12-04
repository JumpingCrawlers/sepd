<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;

class Patrocinador extends Model
{
    //
    protected $table = 'elearning__patrocinador';
    public $primaryKey = 'id_patrocinador';
    public $timestamps = false;
    // para marcar las instancias de Carbon
    protected $dates = [
        'fecha_reg'
    ];

    
    /*****************************************
     * WITH ..... relaciones de patrocinadores
     *
     *****************************************/

    /**
     * Datos de los cursos en los que aparece
     */
    public function cursos() {
        return $this->belongsToMany('App\Curso', 'elearning__patrocinios', 'id_patrocinador', 'id_curso');
    }    


}
