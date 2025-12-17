<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UsuarioCurso;

class CursoExpediente extends Model
{
    /* 3 Ways - Alex R. */

    protected $table = 'cursos_expedientes';
    
    // Saber si el expediente está lleno
    public function hay_plazas() {
        $plazasOcupadas = 0;
        $plazasOcupadas = UsuarioCurso::where('curso_expediente_id', $this->id)->count();

        if($this->plazas > $plazasOcupadas || $this->plazas == null)
            return true;
        else
            return false;
    }
}