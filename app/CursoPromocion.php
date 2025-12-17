<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CursoPromocion extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'cursos_promociones';
    
    // Obtener todas las claves disponibles del curso
    public function claves_disponibles() {
        return $this->hasMany(PromocionClave::class, 'promocion_id', 'promocion_id')->where('usuario_id', null);
    }
}