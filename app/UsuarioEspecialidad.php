<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioEspecialidad extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'usuarios_especialidades';
    protected $fillable = [ 'usuario_id', 'especialidad_id' ];

    /**
     * Obtener la relación con la tabla especialidad
     * 
     * @return Especialidad
     */
    public function especialidad() {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }
}
