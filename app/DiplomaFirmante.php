<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiplomaFirmante extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'diplomas_firmantes';

    // Obtener datos del firmante
    public function firmante() {
        return $this->belongsTo(Firmante::class, 'firmante_id');
    }
    /**
     * 3 Ways - Carlos Colmenarez
     * Obtener el diploma al que pertenece
     */
    // Obtener diploma
    public function diploma() {
        return $this->belongsTo(Diploma::class);
    }

}