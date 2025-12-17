<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncuestaPregunta extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'encuestas_preguntas';

    // Obtener categoria
    public function categoria() {
        return $this->belongsTo(EncuestaCategoria::class, 'categoria_id');
    }

    // Obtener respuestas
    public function respuestas() {
        return $this->hasMany(EncuestaRespuesta::class, 'encuestas_preguntas_id');
    }
}