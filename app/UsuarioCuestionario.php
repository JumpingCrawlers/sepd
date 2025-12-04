<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioCuestionario extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'usuarios_cuestionarios';

    // Ver las respuestas que tiene el usuario en un cuestionario
    public function usuario_respuestas() {
        return $this->hasMany(UsuarioCuestionarioRespuesta::class, 'usuarios_cuestionarios_id')->get();
    }

    // Obtener cuestionario
    public function cuestionario() {
        return $this->belongsTo(Cuestionario::class, 'cuestionario_id');
    }

    /**
     * Obtener el usuario relacionado al cuestionario
     *
     * @return \App\User
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Obtener el estado a mostrar de un cuestionario
     * 
     * @return string
     */
    public function getEstadoCuestionarioAttribute()
    {
        $oportunidades = $this->usuario->cuestionarios()->where('cuestionario_id', $this->cuestionario_id)->count();

        if ($this->usuario->tiempo_finalizado($this->cuestionario->bloque->curso)) return strtoupper($this->estado);
        elseif ($this->estado == "aprobado") return "APROBADO";
        elseif ($this->cuestionario->oportunidades == 0) return "Oportunidades ilimitadas";
        elseif ($oportunidades >= $this->cuestionario->oportunidades) return "No te quedan más oportunidades";
        elseif (($this->cuestionario->oportunidades - $oportunidades) == 1) return "Te queda una última oportunidad";
        else return "Te quedan " . ($this->cuestionario->oportunidades - $oportunidades) . " oportunidades";
    }
    
    /**
     * Comprobar si el cuestionario está aprobado o si la recuperación del cuestionario está aprobada
     * 
     * @return bool
     */
    public function isApproved()
    {
        if ($this->estado == "aprobado") return true;

        if ($this->cuestionario->recuperaciones->count() > 0): // Si hay recuperación
            foreach ($this->cuestionario->recuperaciones as $recuperacion):
                $usuario_recuperacion = $this->usuario->getLastCuestionarioById($recuperacion->id);
        
                if (!$usuario_recuperacion) continue;
                elseif ($usuario_recuperacion->estado != "aprobado") continue;
                
                return true;
            endforeach;

            return false;
        endif;

        return false;
    }
}