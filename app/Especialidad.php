<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'especialidades';

    /**
     * Obtener las subespecialidades
     * 
     * @return Especialidad
     */
    public function subespecialidades() {
        return $this->hasMany(Especialidad::class, 'especialidad_padre')->orderBy('order', 'asc');
    }
}
