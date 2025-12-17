<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioInteres extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'usuarios_intereses';
    protected $fillable = [ 'usuario_id', 'interes_id' ];

    /**
     * Obtener interés
     * 
     * @return Interes
     */
    public function interes() {
        return $this->belongsTo(Interes::class, 'interes_id');
    }
}