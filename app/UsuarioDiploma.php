<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsuarioDiploma extends Model
{
    use SoftDeletes;

    /* 3 Ways - Alexis Bogado */

    protected $table = 'usuarios_diplomas';
    
    public $incrementing = false;
    
    protected $casts = [ 
        'id' => 'string'
    ];

    // Obtener el curso del diploma
    public function curso() {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Obtener el bloque del diploma
    public function bloque() {
        return $this->belongsTo(CursoBloque::class, 'bloque_id');
    }

    // Obtener usuario
    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Obtener usuario curso
    public function usuario_curso() {
        return $this->hasOne(UsuarioCurso::class, 'usuario_id', 'usuario_id')->where('curso_id', $this->curso_id);
    }

    public function fecha_ultima_actividad()
    {
        $fecha_ultima_actividad = $this->usuario_curso->fecha_ultima_actividad();

        if ($fecha_ultima_actividad)
            return date("d-m-Y", strtotime($fecha_ultima_actividad));
        elseif ($this->curso->external)
            return $this->created_at->format('d-m-Y');
        elseif ($this->curso->encuesta)
            return $this->curso->encuesta->created_at->format('d-m-Y');
        else
            return $this->usuario_curso->curso->fecha_fin ? date("d-m-Y", strtotime($this->usuario_curso->curso->fecha_fin)) : '-';
    }
}