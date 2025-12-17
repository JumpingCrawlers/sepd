<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diploma extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $casts = [
        'firmas_adicionales' => 'array',
    ];

    // Obtener los firmantes del diploma
    public function diploma_firmantes() {
        return $this->hasMany(DiplomaFirmante::class, 'diploma_id');
    }

    public function getUrlLogoAttribute () : string
    {
        $rutaWeb = config('app.url_back');

        return $this->logo 
                ? $rutaWeb . '/storage/' . $this->logo 
                : $rutaWeb . '/storage/cursos/migrados/diplomas/logo_SEPD.jpg';
    }

    public function getUrlImageFondoAttribute () : string
    {
        $rutaWeb = config('app.url_back');

        return $this->imagen_fondo ? $rutaWeb . "/storage/" . $this->imagen_fondo : null;
    }
}