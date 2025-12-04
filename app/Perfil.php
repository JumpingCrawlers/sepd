<?php

namespace App;

class Perfil extends Model
{
    protected $table = 'perfiles';
    public $primaryKey = 'id_perfil';
    public $incrementing = false;
    public $timestamps = false;
}
