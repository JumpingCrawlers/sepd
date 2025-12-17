<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UsuarioDiploma;
use App\Curso;
use App\Pagina;
use App\DiplomaCertificado;
use App\Usuario;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AcreditacionesController extends Controller
{
    /* 3 Ways - Alexis Bogado */

    // Mostrar vista acreditaciones
    public function show($tipo = null)
    {
        $pagina = Pagina::getPaginaBySlug('historial_acreditaciones');

        $user = Auth::user();

        $usuario_diplomas = $user->usuario_diplomas_obtiene_diploma()->paginate(10);
        $cursos_creditos = [];
        $creditos = 0.00;

        foreach ($user->usuario_diplomas_obtiene_diploma() as $usuario_diploma) {
            if (in_array($usuario_diploma->curso_id, $cursos_creditos)) continue;
            
            array_push($cursos_creditos, $usuario_diploma->curso_id);
            $creditos += $usuario_diploma->curso->creditos;
        }

        /** 3ways - Euro Fuenmayor
         * Ajustado:
         * Mostrar en tabla un solo diploma por evento que engloba todas las sesiones(actividades).
         * Muestra diplomas de sesiones solo si el diploma pre existe del gestor antiguo
         */
        if ($tipo == "presencial") {
            $creditos = 0.00;
            $years_check_array = [];
            $usuario_diplomas = $user->diplomas_presenciales();
            foreach ($usuario_diplomas as $key => $usuario_diploma) {
                $year = explode('-', $usuario_diploma->created_at)[0];
                $file_pdf = public_path() . "/storage/diplomas/presenciales/Diploma_{$usuario_diploma->id_evento} {$usuario_diploma->id}.pdf";
                if (!file_exists($file_pdf)) {
                    if (!in_array($year, $years_check_array)) {
                        $creditos += $usuario_diploma->creditos;
                        array_push($years_check_array, $year);
                    } else {
                        if ($year < 2018) {
                            $usuario_diplomas->forget($key);
                        }
                    }
                } else {
                    $creditos += $usuario_diploma->creditos;
                }
            }
        }
        // si tiene un diploma certificado
        $certificados = DiplomaCertificado::where("alumno_id",$user->id)->get();

        $certificado_curso = [];

        foreach($certificados as $cert){
            $certificado_curso[$cert->curso_id] = Curso::select("titulo")->where("id",$cert->curso_id)->first();
        }
        
        return view('formacion.acreditaciones.' . (is_null($tipo) ? 'online' : $tipo), compact('usuario_diplomas', 'creditos', 'user', 'pagina','certificados','certificado_curso'));
    }

    // Buscar diploma
    public function searchDiploma()
    {
        $pagina = Pagina::getPaginaBySlug('historial_acreditaciones');

        return view('acreditaciones.validador.index', compact('pagina'));
    }

    // Mostrar información de diploma
    public function validarDiploma(Request $request)
    {
        if (!$request->input('code')) return redirect()->back()->withErrors(['error' => 'Debes introducir un código']);
        $evento = "curso";
        $diploma = UsuarioDiploma::find($request->input('code'));
        if(!$diploma){
            $diploma = DB::table("certificados_corporativos_code")->where("code",$request->input('code'))->first();

            if (!$diploma){
                return redirect()->back()->withErrors(['error' => 'No existe ningún diploma con este código']);
            }else{

                $usuario = DB::table("certificados_corporativos")->where("id",$diploma->certificado_id)->first();

                if($usuario){
                    $nombre = Usuario::where("id",$usuario->usuario_id)->first();
                    if($nombre){
                        $diploma->usuario = $usuario;
                        $diploma->usuario->nombre_completo = $nombre->nombre." ".$nombre->apellidos;
                    }else{
                        return redirect()->back()->withErrors(['error' => 'No existe ningún diploma con este código']);
                    }
                }else{
                    return redirect()->back()->withErrors(['error' => 'No existe ningún diploma con este código']); 
                }

                if($diploma->congreso_id !=null ){
                    $congreso = DB::table("congresos_sesiones")->where("id",$diploma->congreso_id)->first();
                    if($congreso){
                        $diploma->curso = $congreso;
                        $diploma->curso->titulo = $congreso->nombre;
                        $evento = 'congreso';
                    }else{
                        return redirect()->back()->withErrors(['error' => 'No existe ningún diploma con este código']); 
                    }
                    
                }else if($diploma->titulo_libro !=null){
                    $diploma->curso = new \stdClass();
                    $diploma->curso->titulo = $diploma->titulo_libro;
                    $evento = 'libro';
                }else if($diploma->nombre_congreso !=null){
                    $diploma->curso = new \stdClass();
                    $diploma->curso->titulo = $diploma->nombre_congreso;
                    $evento = "congreso";
                }else{
                   return redirect()->back()->withErrors(['error' => 'No existe ningún diploma con este código']); 
                }
                $diploma->created_at = Carbon::parse($diploma->created_at);
                $diploma->id = $diploma->code;
            }
        }

        

        $pagina = Pagina::getPaginaBySlug('historial_acreditaciones');
        $meses = [
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

        return view('acreditaciones.validador.resultado', compact('pagina', 'diploma', 'meses','evento'));
    }
}
