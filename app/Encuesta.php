<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    /* 3 Ways - Alexis Bogado */

    // Obtener preguntas
    public function preguntas() {
        return $this->hasMany(EncuestaPregunta::class, 'encuestas_id')->orderBy('categoria_id')->orderBy('orden');
    }
}