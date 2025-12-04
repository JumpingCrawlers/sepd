<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Usuario;
use App\Firmante;
use PDF;

/**
 *  Clase del controlador de VPCR. - Alex R.
 */
class VpcrsController extends Controller
{
    // Meses
    private $meses = [
        1 => "Enero",
        2 => "Febrero",
        3 => "Marzo",
        4 => "Abril",
        5 => "Mayo",
        6 => "Junio",
        7 => "Julio",
        8 => "Agosto",
        9 => "Septiembre",
        10 => "Octubre",
        11 => "Noviembre",
        12 => "Diciembre",
    ];

    // Generar diploma
    public function mostrar()
    {

        if(!$_SESSION['sepd_link_user']) return redirect('/login?route=vpcr');


        $usuario = Usuario::findOrFail($_SESSION['sepd_link_user']);
        $usuario_vpcr = $usuario->vpcr();
        
        //Que no se pueda acceder a la ruta del diploma si no está concedido el certificado
        if($usuario_vpcr->get()->first()->fecha_concedido == null) return redirect('/vpcr');
        
        //Ordenar por tipos de competencias
        $competencias = $usuario_vpcr
                    ->join('usuarios_vpcrs_competencias', 'usuarios_vpcrs_competencias.usuarios_vpcrs_id', '=', 'usuarios_vpcrs.id')
                    ->join('competencias', 'usuarios_vpcrs_competencias.competencias_id', '=', 'competencias.id')
                    ->orderBy('competencias.competencias_tipos_id')
                    ->get();
        //Obtener firmante para el vpcr. El id=1 debería ser el apropiado.
        $firmante = Firmante::find(1);
 
        $view = view('base.certificado_vpcr', compact('usuario','competencias','firmante'));
        
        /* Replacements */
        
        $view = $this->replace([
            "nombre" => $usuario->nombre,
            "apellidos" => $usuario->apellidos,
            "tratamiento" => $usuario->tratamiento,
            "d" => date("j", strtotime($usuario->created_at)),
            "m" => $this->meses[date("n", strtotime($usuario->created_at))],
            "a" => date("Y", strtotime($usuario->created_at))
        ], $view);

        /* End replacements */

        $pdf = PDF::loadHTML($view);
        return $pdf->stream("CertificadoVpcr.pdf");
    }

    // Obtener fecha
    private function fecha($date) {
        $dia = date("j", strtotime($date));
        $mes = $this->meses[date("n", strtotime($date))];
        $año = date("Y", strtotime($date));

        return "{$dia} de {$mes} de {$año}";
    }

    // Reemplazar llaves
    private function replace($replacements, $source) {
        foreach ($replacements as $key => $value) $source = str_replace("{{$key}}", $value, $source);
        return $source;
    }


}
