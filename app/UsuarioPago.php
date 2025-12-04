<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioPago extends Model {

    /* 3 Ways - Alexis Bogado */

    protected $table = 'usuarios_pagos';

    /**
     * Obtener el usuario asociado al pago
     *
     * @return \App\User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
