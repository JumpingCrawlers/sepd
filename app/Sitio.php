<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sitio extends Model
{
    protected $table = 'sitio';

    static function ultima_act() {
        $fecha_ult_act = date('d-m-Y', strtotime(static::first()->updated_at));

        return $fecha_ult_act;
    }
}
