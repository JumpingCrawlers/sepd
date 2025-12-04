<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiplomaCertificado extends Model
{
    protected $table = 'diplomas_certificados';

    protected $fillable = [
        'curso_id',
        'alumno_id',
        'nombre_diploma',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }
}

