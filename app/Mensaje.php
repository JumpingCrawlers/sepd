<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Mensaje extends Model
{
    /**
     * 3 Ways Alex
     * Obtener Datos del usuario emisor
     */
    public function datosEmisor() {
        return $this->belongsTo(User::class, "emisor");
    }  

    /**
     * 3 Ways Alex
     * Obtener Datos del usuario receptor
     */
    public function datosReceptor() {
        return $this->belongsTo(User::class, "receptor");
    }  
}