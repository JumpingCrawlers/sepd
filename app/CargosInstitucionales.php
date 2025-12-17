<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CargosInstitucionales extends Model
{
    protected $table = 'cargos_institucionales';

    protected $fillable = [
        'nombre_cargoi',
        'sociedad_id',
        'borrado',
    ];

    /**
     * Obtener el CertificadoSocio
     * 
     * @return CertificadosSocio|null
     */
    public function certificados_socio ()
    {
        return $this->hasOne(CertificadosSocio::class, 'cargo_institucional_id');
    }
}
