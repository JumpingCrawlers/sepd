<?php

namespace App\Http\Controllers;

use App\Curso;
use App\DiplomaPresencial;
use App\UsuarioDiploma;
use PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use App\User;
use App\UsuarioCurso;
use Illuminate\Http\Request;

class DiplomasController extends Controller
{
    /* 3 Ways - Alexis Bogado */

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
    public function mostrar($id)
    {
        /**
         *  3ways Euro Fuenmayor
         *  Agregada lógica para determinar cuando un diploma se trata de una acreditación de formación presencial o no
         *  Si es presencial, entonces verifica si existe la fichero PDF de acreditación, caso contrario no devuelve que no fue encontrado
         *  si no es presencial, se renderiza el html para el fichero PDF para acreditación de formación en línea
         **/
        $presencial = strpos($id, 'P_') !== false;
        if ($presencial) {
            
            $diploma_id = str_replace('P_', '', $id);

            $diploma_presencial = DiplomaPresencial::findOrFail($diploma_id);

            $file_pdf = public_path() . "/storage/diplomas/presenciales/Diploma_{$diploma_presencial->id_evento} {$diploma_id}.pdf";

            if (file_exists($file_pdf)) {
                
                $headers = [
                    'Content-Type' => 'application/pdf',
                ];

                return response()->file($file_pdf, $headers);

            } else {
                /**
                 *  3ways Euro Fuenmayor
                 *  Agregada Lógica para renderizar html para el fichero PDF para acreditación de formación presencial
                 *
                 **/

                // $diploma = \App\Diploma::find($diploma_presencial->diploma_id);
                $view = view('base.diploma_presencial', compact('diploma_presencial'));
                $nombre = !is_null($diploma_presencial->nombre) ? $diploma_presencial->nombre : '';
                $apellidos = !is_null($diploma_presencial->apellidos) ? $diploma_presencial->apellidos : '';
                $nombre .= " {$apellidos}";
                $acreditacion_evento = $diploma_presencial->acreditacion_evento;
                $header = !is_null($acreditacion_evento) ? $acreditacion_evento->texto_diploma_cabecera : 'Ha asistido a las Actividades del Programa de Formación Continuada';
                // $header_array = explode(' ',  $header);
                // if (isset($header_array[4])) {
                //     if (strtolower($header_array[4]) == 'congreso') {
                //         $header_array[2] = 'al<Strong>';
                //         $header_array[12] = '</Strong>celebrado';
                //         $header = implode(' ', $header_array);
                //     }
                // }
                $url_back = config('app.url_back');
                
                $datos_acreditacion = !is_null($acreditacion_evento) ? $acreditacion_evento->texto_diploma_datos_acreditacion : 'Programa Formativo Acreditado por la Comisión de la Formación Continuada de las Profesiones Sanitarias obteniendo';
                $user = Auth::user();
                $year = explode(' ', $diploma_presencial->created_at->toDateTimeString())[0];
                list($cells, $total_creditos) = $user->tabla_de_actividades_y_creditos_por_evento_diploma_presencial($diploma_presencial);
                $datos_acreditacion = str_replace('[creditos]', "<strong>{$total_creditos}</strong> créditos", $datos_acreditacion);
                $texto_advertencia_array = explode(' ',  $acreditacion_evento->texto_diploma_advertencia);
                $texto_advertencia_array[0] = '';
                $texto_advertencia = implode(' ', $texto_advertencia_array);
                $texto_advertencia = is_null($texto_advertencia) ? '' : '<h6 class="texto_advertencia">' . $texto_advertencia . '</h6>';
                $fecha = $acreditacion_evento->obtener_fecha_fin_diploma('fecha');
                $fecha = $fecha == '' ? date("j") . " de " . $this->meses[date("n")] . " de " . date("Y") : $fecha;
                $firma_bloque_array = explode(',',  $acreditacion_evento->firma);
                $firma_identificador = $firma_bloque_array[0];
                $firma_cargo = trim($firma_bloque_array[1]);
                $firma_filename = Str::slug($firma_identificador);
                $lugar = !empty($acreditacion_evento->lugar) ? $acreditacion_evento->lugar . ',' : '';
                $image_firma = 'src="' . $url_back . 'storage/acreditaciones/firmas/' . $firma_filename . '.png" ';
                if (strpos($acreditacion_evento->logo, ',') !== false) {
                    echo 'true';
                }
                $logo0 = strpos($acreditacion_evento->logo, ',') !== false ? explode(',', $acreditacion_evento->logo)[0] : $acreditacion_evento->logo;
                $image_logo = 'src="' . $url_back . 'storage/acreditaciones/logos/' . $logo0 . '.png" ';
                $image_acreditacion = $url_back . 'storage/acreditaciones/logos/acreditacion.jpg';
                $image_cabecera = $url_back . 'storage/acreditaciones/logos/SED-header.jpg';
                $uems_class = ($acreditacion_evento->nombre == 'SED 2016') ? 'class="extra_logos"' : 'class="d-none"';
                $seaformec_class = ($acreditacion_evento->nombre == 'SED 2016') ? 'class="extra_logos"' : 'class="d-none"';
                $logos_acreditadores_multiples = ($acreditacion_evento->nombre == 'SED 2016') ? 'logos_acreditadores_multiples' : '';
                $texto_opcional = $acreditacion_evento->texto_diploma_opcional;

                $logo_class = '';
                if ($acreditacion_evento->logo == 'logo1' || $acreditacion_evento->logo == 'logo2' || $acreditacion_evento->logo == 'logo3') {
                    $logo_class = 'class="logos_largo" ';
                }
                if (strpos($acreditacion_evento->logo, ',') !== false) {
                    $logo_class = 'class="logos_multiples" ';
                } else {
                    if ($acreditacion_evento->logo == 'logo0') {
                        $logo_class = 'class="logo_solo" ';
                    }
                }

                $titulo_actividades = $year > 2017 ? '' : '<h2 class="texto_actividades">ACTIVIDADES DEL PROGRAMA en las que ha participado:</h2>';

                $horas_formativas = str_replace('.00', '', $diploma_presencial->tiempo);

                $horas_formativas = number_format($horas_formativas, 1, ',', ' ');

                $view = $this->replace([
                    "nombre" => $nombre,
                    "header" => $header,
                    "cells" => $cells,
                    "logos_acreditadores_multiples" => $logos_acreditadores_multiples,
                    "logo_class" => $logo_class,
                    "uems_class" => $uems_class,
                    "seaformec_class" => $seaformec_class,
                    "datos_acreditacion" => $datos_acreditacion,
                    "texto_opcional" => $texto_opcional,
                    "texto_advertencia" => $texto_advertencia,
                    "fecha" => $fecha,
                    "lugar" => $lugar,
                    "firma_identificador" => $firma_identificador,
                    "firma_cargo" => $firma_cargo,
                    "image_firma" => $image_firma,
                    "image_logo" => $image_logo,
                    "image_acreditacion" => '"' . $image_acreditacion . '"',
                    "image_cabecera" => '"' . $image_cabecera . '"',
                    "titulo_actividades" => $titulo_actividades,
                    "horas_formativar" => $horas_formativas,
                    "entidad" => $diploma_presencial->entidad,
                    "num_expediente" => $diploma_presencial->num_expediente,
                    "creditos" => str_replace('.', ',', $diploma_presencial->creditos)
                ], $view);
            }
        } else {
            $usuario_diploma = UsuarioDiploma::findOrFail($id);
            $rutaWeb = env('APP_URL', "https://www.sepd.es");
            $view = view('base.diploma', compact('usuario_diploma', 'rutaWeb'));

            $filename = Str::slug($usuario_diploma->curso->titulo) . '.pdf';
            /**
             * Fecha Fin Curso
             * En el caso que la fecha del fin del diploma del usuario es diferente a 12/12/2012
             * Le asignamos la fecha de creación del registro del diploma
             */
            if ($this->fecha($usuario_diploma->created_at) != '12 de Diciembre de 2012') {
                $fin_curso = $usuario_diploma->created_at;
                $d = date("j", strtotime($usuario_diploma->created_at));
                $m = $this->meses[date("n", strtotime($usuario_diploma->created_at))];
                $a = date("Y", strtotime($usuario_diploma->created_at));
            } else {
                /**
                 * En el caso de que la fecha es 12/12/2012
                 * Sacamos la facha del ultimo item visto por el usuario
                 */
                $fin_curso = $usuario_diploma->usuario_curso->fecha_ultima_actividad();
                // Modificar la fecha que aparece en la firma del diploma
                // En caso de que es 12 de Diciembre de 2012
                // ponemos la fecha de hoy
                $current = Carbon::today();
                $d = $current->day;
                $m = $this->meses[$current->month];
                $a = $current->year;
            }
            /* Replacements */
            // Averiguar si el numero de expediente existe
            $txt_expediente = $usuario_diploma->bloque_id ? $usuario_diploma->bloque->expediente : $usuario_diploma->curso->expediente;
            $txt_expediente = $txt_expediente ? " con nº de expediente " . $txt_expediente . "," : '';
            $view = $this->replace([
                // Variables diploma estandar
                "txt_expediente" => $txt_expediente,
                "txt_creditos" => ", con " . $usuario_diploma->curso->creditos . " Créditos de formación continuada para la profesión médica",
                // FIN variables diploma estandar
                "nombre" => $usuario_diploma->usuario->nombre,
                "apellidos" => $usuario_diploma->usuario->apellidos,
                "tratamiento" => $usuario_diploma->usuario->tratamiento,
                "inicio" => $this->fecha($usuario_diploma->usuario_curso->created_at),
                "fin" => $this->fecha($fin_curso),
                "curso" => $usuario_diploma->curso->titulo,
                "modulo" => ($usuario_diploma->bloque_id ? $usuario_diploma->bloque->titulo : ""),
                "expediente" => ($usuario_diploma->bloque_id ? $usuario_diploma->bloque->expediente : (($usuario_diploma->usuario_curso->expediente) ? $usuario_diploma->usuario_curso->expediente->expediente : "")),
                "fecha_inicio_expediente" => ($usuario_diploma->bloque_id ? "" : (($usuario_diploma->usuario_curso->expediente) ? $this->fecha($usuario_diploma->usuario_curso->expediente->fecha_inicio) : "")),
                "fecha_fin_expediente" => ($usuario_diploma->bloque_id ? "" : (($usuario_diploma->usuario_curso->expediente) ? $this->fecha($usuario_diploma->usuario_curso->expediente->fecha_fin) : "")),
                "creditos" => $usuario_diploma->curso->creditos,
                "horas" => $usuario_diploma->usuario_curso->horas_lectivas,
                "d" => $d,
                "m" => $m,
                "a" => $a,
            ], $view);
        }

        $pdf = PDF::loadHTML($view)->setOptions(['isHtml5ParserEnabled' >= true, 'isRemoteEnabled' => true]);

        $pdf->setPaper('a4', 'portrait');

        $dompdf = $pdf->getDomPDF();

        $dompdf->setHttpContext(stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ],
        ]));

        $dompdf->render();

        return $pdf->stream(isset($filename) && $filename ? $filename : "Diploma.pdf");
    }

    // Obtener fecha
    private function fecha($date)
    {
        if ($date == null) return "";

        $dia = date("j", strtotime($date));
        $mes = $this->meses[date("n", strtotime($date))];
        $año = date("Y", strtotime($date));

        return "{$dia} de {$mes} de {$año}";
    }

    // Reemplazar llaves
    private function replace($replacements, $source)
    {
        foreach ($replacements as $key => $value) $source = str_replace("{{$key}}", $value, $source);
        return $source;
    }

    // Comprobar si al usuario le corresponde diploma
    public static function comprobar_diploma($usuario_curso, &$usuario_diploma = null)
    {
        // Ya tiene diploma
        if ($usuario_curso->usuario->usuario_diplomas->where("curso_id", $usuario_curso->curso->id)->first()) :
            return false;
        endif;

        if ($usuario_curso->obtiene_diploma()) :
            $usuario_diploma = self::storeDiploma($usuario_curso);
            return $usuario_diploma;
        endif;

        return false;
    }

    // Crear nuevo diploma
    private static function storeDiploma($usuario_curso)
    {
        $usuario_diploma = new UsuarioDiploma;
        $usuario_diploma->id = self::newDiplomaId(8);
        $usuario_diploma->usuario_id = $usuario_curso->usuario_id;
        $usuario_diploma->curso_id = $usuario_curso->curso_id;
        $usuario_diploma->save();

        return $usuario_diploma;
    }

    // Generar id de diploma
    private static function newDiplomaId($length)
    {
        return substr(base_convert(sha1(time() . uniqid(mt_rand())), 16, 36), 0, $length);
    }
    
    protected function manualDigestivoSepdDiploma (Request $request)
    {
        logger('manualDigestivoSepdDiploma');
        
        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json(["error" => [
                "message" => "No se encontró el usuario",
                "details" => "Por favor, verifica los datos proporcionados e intenta nuevamente."
            ]], 500);

        try {
            // $usuario_curso = $user->usuario_cursos->where('curso_id', $curso_id)->first();
            
            $curso = Curso::where('external_curso_id', $request->curso_id)->first();

            if (!$curso)
                return response()->json(["error" => [
                    "message" => "No se encontró el curso del usuario",
                    "details" => "Por favor, verifica los datos proporcionados e intenta nuevamente."
                ]], 500);

            $usuario_curso = UsuarioCurso::where([
                'usuario_id' => $user->id,
                'curso_id' => $curso->id
            ])->first();
            
            if (!$usuario_curso) {
                $usuario_curso = new UsuarioCurso;
            
                $usuario_curso->usuario_id = $user->id;
    
                $usuario_curso->curso_id = $curso->id;
    
                $usuario_curso->curso_expediente_id = $curso->expedientePlazaLibre();
    
                $usuario_curso->save();
            }
            
            if ($request->finalizado)
                $usuario_diploma = self::storeDiploma($usuario_curso);
    
    
            return response()->json(['message' => 'success']);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

}
