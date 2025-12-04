<?php

namespace App;
use Illuminate\Support\Facades\DB;
use App\UsuarioVpcr;

class Usuario extends User
{
    /* 3 Ways - Alexis Bogado */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'tratamiento', 'nombre', 'apellidos', 'dni', 'email', 'password' ];


    /**
     * 3 Ways - Alex R.
     * Obtener el vpcr
     */
    public function vpcr()
    { 
        $vpcr = UsuarioVpcr::where('usuarios_id',$this->id)->orderBy('fecha_concedido', 'DESC');

        return $vpcr;
    }


    /**
     * 3 Ways - Alex R.
     * Obtener el último usuarios_vpcrs del usuario
     */
    public function fecha_vpcr()
    { 
        $vpcr = UsuarioVpcr::where('usuarios_id',$this->id)->orderBy('fecha_concedido', 'DESC')->get()->first();

        $carbon_date = \Carbon\Carbon::parse($vpcr->fecha_concedido);
        $carbon_date->addYears(5);

        return $carbon_date;
    }


    /**
     * 3 Ways - Alex R.
     * Obtener la fecha concedido del usuario_vpcr
     */
    public function fecha_concedido_vpcr()
    { 
        $vpcr = UsuarioVpcr::where('usuarios_id',$this->id)->orderBy('fecha_concedido', 'DESC')->get()->first();

        $carbon_date = \Carbon\Carbon::parse($vpcr->fecha_concedido);

        return $carbon_date;
    }
}
