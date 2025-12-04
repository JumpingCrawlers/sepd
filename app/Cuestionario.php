<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//3 Ways - Carlos Colmenarez
class Cuestionario extends Model
{
    protected $table = 'cursos_cuestionarios';

    public function preguntas() {
        return $this->hasMany(CuestionarioPregunta::class, 'cuestionarios_id')->whereNull('deleted_at')->inRandomOrder();
    }

    public function bloque() {
        return $this->belongsTo(CursoBloque::class);
    }

    /* 3 Ways - Alexis Bogado */

    /**
     * Obtener los cuestionarios de recuperación de un cuestionario
     * 
     * @return Cuestionario
     */
    public function recuperaciones() {
        return $this->hasMany(Cuestionario::class, 'id_superior')->whereNull('deleted_at');
    }

    /**
     * Obtener el cuestionario principal si es un cuestionario de recuperación
     * 
     * @return Cuestionario
     */
    public function cuestionario_superior() {
        if (!$this->id_superior || $this->id_superior <= 0) return null;

        return $this->belongsTo(Cuestionario::class, 'id_superior');
    }

    /**
     * Obtener texto de oportunidades del cuestionario
     * 
     * @return string
     */
    public function getTextoOportunidadesAttribute() {
        if ($this->oportunidades == 0) return "Oportunidades ilimitadas";
        elseif ($this->oportunidades == 1) return "Tienes una oportunidad";
        else return "Tienes {$this->oportunidades} oportunidades";
    }
}
