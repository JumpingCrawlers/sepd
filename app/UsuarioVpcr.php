<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/* 3 Ways - Alex R.  */
class UsuarioVpcr extends Model
{
    protected $table = 'usuarios_vpcrs';

    /**
     * 3 Ways - Alexis Bogado
     * Obtener usuario
     * 
     * @return App\User
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuarios_id');
    }
}