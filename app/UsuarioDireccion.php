<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioDireccion extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'usuarios_direcciones';

    /**
     * Obtener la provincia
     * 
     * @return Provincia
     */
    public function provincia() {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    /**
     * Obtener el país
     * 
     * @return Pais
     */
    public function pais() {
        return $this->belongsTo(Pais::class, 'pais_id');
    }
}
