<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;

class Tutor extends Model
{
    //
    protected $table = 'elearning__tutor';
    public $primaryKey = 'id_tutor';
    public $timestamps = false;
    // para marcar las instancias de Carbon
    protected $dates = [
        'fecha_reg'
    ];

    
    /***************************************
     * WITH ..... relaciones de tutores
     *
     **************************************/

    /**
     * Datos de los cursos asociados
     */
    public function cursos() {
        return $this->belongsToMany('App\Menu', 'elearning__tutorizacion', 'id_tutor', 'id_curso');
    }    


}
