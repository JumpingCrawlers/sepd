<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncuestaCategoria extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'encuestas_categorias';

    // Obtener preguntas
    public function preguntas() {
        return $this;
    }
}