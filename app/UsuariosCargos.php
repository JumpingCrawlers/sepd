<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuariosCargos extends Model
{
    protected $table = 'usuarios_cargos';

    protected $fillable = [
        'usuario_id',
        'cargo_institucional_id',
        'fecha_inicio',
        'fecha_fin',
        'responsable',
    ];

    /**
     * Obtener el cargo institucionales
     * 
     * @return CargosInstitucionales
     */
    public function cargos_institucionales()
    {
        return $this->hasOne(CargosInstitucionales::class, 'id', 'cargo_institucional_id');
    }

    /**
     * Obtener el usuario
     * 
     * @return Usuario
     */
    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id', 'usuario_id');
    }

    public function cargo()
    {
        return $this->belongsTo(CargosInstitucionales::class, 'cargo_institucional_id');
    }
}
