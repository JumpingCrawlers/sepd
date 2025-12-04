<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//3 Ways - Carlos Colmenarez
class CuestionarioPregunta extends Model
{
    protected $table = 'cursos_cuestionarios_preguntas';

    public function respuestas() {
        return $this->hasMany(CuestionarioPreguntaRespuesta::class, 'cuestionarios_preguntas_id')->whereNull('deleted_at')->inRandomOrder();
    }
}
