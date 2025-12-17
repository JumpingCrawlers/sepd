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
use App\Diploma;

use Illuminate\Http\Response;

class DiplomaController extends Controller
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

    /**
     * Generar el Diploma en PDF.
     * El diploma pude ser Presencial o no
     *
     * @param string|int $id
     * @return Response|string
     */
    public function show (string|int $id) : Response|string
    {
        return $this->isPresencialById($id) ? $this->generateViewDiplomaPresencial($id) : $this->generateViewDiploma($id);
    }
    
    protected function generateViewDiploma ($id)
    {
        // Obtiene el registro de UsuarioDiploma por ID
        $usuario_diploma = UsuarioDiploma::findOrFail($id);
        
        // Obtiene el curso y la URL base del sitio
        $curso = $usuario_diploma->curso;
        
        $rutaWeb = config('app.url_back');
        
        $diploma = $curso->diploma_id && $curso->diploma ? $curso->diploma : null;

        // Diplomas y valores predeterminados
        $imagen_fondo = $diploma ? $diploma->url_imagen_fondo : null;

        // Logo principal, se establece un logo predeterminado si no se encuentra otro
        $logo = $diploma ? $diploma->url_logo : $rutaWeb . '/storage/cursos/migrados/diplomas/logo_SEPD.jpg';

        // Nombre del usuario con tratamiento y apellidos
        $name = "{$usuario_diploma->usuario->tratamiento} {$usuario_diploma->usuario->nombre} {$usuario_diploma->usuario->apellidos}";

        // Encabezado del diploma
        $encabezado = $diploma->encabezado ?? "Ha cursado desde el {inicio} hasta el {fin}, las Actividades del Programa de Formación Continuada:";

        // Título del diploma (se toma del diploma o del curso si no está disponible)
        $title = $diploma && $diploma->nombre_curso ? $diploma->nombre_curso : $curso->titulo;

        // Texto del cuerpo del diploma, se adapta dependiendo de si hay créditos
        $conte1 = $curso->creditos ? '{txt_expediente}' : '';
        $conte2 = $curso->creditos ? '{txt_creditos}' : '';
        // dd($diploma->cuerpo);
        $text1 = $diploma ? $diploma->cuerpo : "Esta actividad docente$conte1 está acreditada por la Comisión de Formación Continuada de las Profesiones Sanitarias de la Comunidad de Madrid-Sistema Nacional de Salud$conte2. Los créditos de esta actividad formativa no son aplicables a los profesionales, que participen en la misma, y que estén formándose como especialistas en Ciencias de la Salud, es decir, los internos residentes de las profesiones citadas.";

        $text1 = str_replace('XXXXXXX', $usuario_diploma->usuario->dni ?? '', $text1);
        $text1 = str_replace('{XXXXXXX}', $usuario_diploma->usuario->dni ?? '', $text1);

        // Organizadores: Recoge los logos de los organizadores si están configurados
        $patrocinadores = $this->obtenerImagenes($diploma, $curso, 'patrocinadores', 'logo_patrocinadores', 'img_patrocinadores');

        // Organizadores: Recoge los logos de los organizadores si están configurados
        $organizadores = $this->obtenerImagenes($diploma, $curso, 'organizadores', 'logo_organizadores', 'img_organizadores');

        // Avaladores: Recoge los logos de los avaladores si están configurados
        $avaladores = $this->obtenerImagenes($diploma, $curso, 'avaladores', 'logo_avaladores', 'img_avaladores');

        // Código único del diploma
        $code = $usuario_diploma->id;

        $view = view('base.diploma_new', [
            'diploma' => $diploma,
            'lugarFecha' => $diploma ? $diploma->lugarFecha : null,
            'usuario_diploma' => $usuario_diploma,
            'rutaWeb' => $rutaWeb,
            'imagen_fondo' => $imagen_fondo,
            'logo' => $logo,
            'name' => $name,
            'encabezado' => $encabezado,
            'title' => $title,
            'text1' => $text1,
            'img_acreditacion' => $diploma ? $diploma->img_acreditacion : null,
            'patrocinadores' => $patrocinadores,
            'organizadores' => $organizadores,
            'avaladores' => $avaladores,
            'code' => $code,
        ]);

        // Genera el nombre del archivo PDF a partir del título del curso
        $filename = Str::slug($curso->titulo) . '.pdf';

        /**
         * Fecha Fin Curso:
         * Si la fecha de creación del diploma del usuario es diferente a "12 de Diciembre de 2012",
         * se usa la fecha de creación del registro del diploma como la fecha de finalización del curso.
         */
        if ($this->fecha($usuario_diploma->created_at) != '12 de Diciembre de 2012') {
            // Asigna la fecha de creación del diploma
            $fin_curso = $usuario_diploma->created_at;
            // $d = date("j", strtotime($usuario_diploma->created_at)); // Día
            // $m = $this->meses[date("n", strtotime($usuario_diploma->created_at))]; // Mes
            // $a = date("Y", strtotime($usuario_diploma->created_at)); // Año
        } else {
            /**
             * Si la fecha es "12/12/2012", asigna la fecha del último ítem visto por el usuario.
             * También actualiza la fecha de firma del diploma a la fecha actual.
             */
            $fin_curso = $usuario_diploma->usuario_curso->fecha_ultima_actividad();
            // $fin_curso = $usuario_diploma->created_at;
        }
        

        $current = Carbon::today();
        $d = $current->day;
        $m = $this->meses[$current->month];
        $a = $current->year;

        /* Replacements */
        // Se verifica si existe un número de expediente y se prepara el texto correspondiente
        $txt_expediente = $usuario_diploma->bloque_id 
                        ? $usuario_diploma->bloque->expediente 
                        : $curso->expediente;

        $txt_expediente = $txt_expediente 
                        ? " con nº de expediente " . $txt_expediente . "," 
                        : '';

        // Realiza los reemplazos de variables en la vista del diploma
        $view = $this->replace([
            // Variables estándar del diploma
            "txt_expediente" => $txt_expediente,
            "txt_creditos" => ", con " . $curso->creditos . " Créditos de formación continuada para la profesión médica",
            
            // Información personal del usuario
            "nombre" => $usuario_diploma->usuario->nombre,
            "apellidos" => $usuario_diploma->usuario->apellidos,
            "tratamiento" => $usuario_diploma->usuario->tratamiento,
            
            // Fechas de inicio y fin del curso
            "inicio" => $this->fecha($usuario_diploma->usuario_curso->created_at),
            "fin" => $this->fecha($fin_curso),
            
            // Información del curso
            "curso" => $curso->titulo,
            "modulo" => $usuario_diploma->bloque_id ? $usuario_diploma->bloque->titulo : "",
            
            // Información del expediente
            "expediente" => $usuario_diploma->bloque_id 
                            ? $usuario_diploma->bloque->expediente 
                            : ($usuario_diploma->usuario_curso->expediente 
                            ? $usuario_diploma->usuario_curso->expediente->expediente 
                            : ""),
            "fecha_inicio_expediente" => $usuario_diploma->bloque_id 
                                        ? "" 
                                        : ($usuario_diploma->usuario_curso->expediente 
                                        ? $this->fecha($usuario_diploma->usuario_curso->expediente->fecha_inicio) 
                                        : ""),
            "fecha_fin_expediente" => $usuario_diploma->bloque_id 
                                    ? "" 
                                    : ($usuario_diploma->usuario_curso->expediente 
                                        ? $this->fecha($usuario_diploma->usuario_curso->expediente->fecha_fin) 
                                        : ""),
            
            // Créditos y horas del curso
            "creditos" => $curso->creditos,
            "horas" => $usuario_diploma->usuario_curso->horas_lectivas,
            
            // Fecha actual (día, mes, año)
            "d" => $d,
            "m" => $m,
            "a" => $a,
        ], $view);
        
        return $this->renderFromView ($view, $filename);
    }

    protected function generateViewDiplomaPresencialEspecial (DiplomaPresencial $diploma_presencial, array $datos)
    {
        $rutaWeb = config('app.url_back');

        $diploma = $diploma_presencial->diploma;

        // Organizadores: Recoge los logos de los organizadores si están configurados
        $organizadores = $this->obtenerImagenes($diploma, null, 'organizadores', 'logo_organizadores', 'img_organizadores');

        // Avaladores: Recoge los logos de los avaladores si están configurados
        $avaladores = $this->obtenerImagenes($diploma, null, 'avaladores', 'logo_avaladores', 'img_avaladores');
        
        // Organizadores: Recoge los logos de los organizadores si están configurados
        $patrocinadores = $this->obtenerImagenes($diploma, null, 'patrocinadores', 'logo_patrocinadores', 'img_patrocinadores');

        // if ($diploma_presencial->created_at instanceof Carbon) {
        //     $datos['d'] = $diploma_presencial->created_at->format('d');
        //     $datos['m'] = $this->meses[intval($diploma_presencial->created_at->format('m'))];
        //     $datos['a'] = $diploma_presencial->created_at->format('Y');
        // }

        $view = view('base.diploma_new', [
            'rutaWeb' => $rutaWeb,
            'lugarFecha' => $diploma->lugarFecha,
            'img_acreditacion' => $diploma->img_acreditacion ? asset('storage/' .  $diploma->img_acreditacion) : null,
            'usuario_diploma' => null,
            'diploma' => $diploma,
            'imagen_fondo' => $diploma->url_imagen_fondo,
            'logo' => $diploma->url_logo,
            'name' => $datos['nombre'],
            'encabezado' => null,
            'title' => $diploma_presencial->evento,
            // $datos['header']
            'text1' => str_replace('XXXXXXX', isset($datos['dni']) ? $datos['dni'] : '', $diploma->cuerpo),
            'patrocinadores' => $patrocinadores,
            'organizadores' => $organizadores,
            'avaladores' => $avaladores,
            'code' => $datos['id']
        ]);

        return $this->renderFromView (
            $this->replace($datos, $view)
        );
    }

    protected function generateViewDiplomaPresencial ($id)
    {
        $id_usuario = $id;

        $diploma_id = str_replace('P_', '', $id);

        //$usuario_diploma = UsuarioDiploma::findOrFail($id);

        $diploma_presencial = DiplomaPresencial::with('diploma')->findOrFail($diploma_id);

        $file_pdf = public_path() . "/storage/diplomas/presenciales/Diploma_{$diploma_presencial->id_evento} {$diploma_id}.pdf";

        if (file_exists($file_pdf)) {

            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            return response()->file($file_pdf, $headers);

        } else {

            $datos = $this->getDatosFromPresencial($diploma_presencial);

            $datos["id"] = str_replace('.pdf', '',$diploma_id);
            
            if ($diploma_presencial->diploma) {
                return $this->generateViewDiplomaPresencialEspecial($diploma_presencial, $datos);
            } 

            $view = view('base.diploma_presencial', compact('diploma_presencial'));

            return $this->renderFromView (
                $this->replace($datos, $view)
            );
        }
    }

    protected function getDatosFromPresencial (DiplomaPresencial $diploma_presencial): array
    {
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
        // Auth::user()
        $user = User::where('email', $diploma_presencial->email)->first();
        $year = explode(' ', $diploma_presencial->created_at->toDateTimeString())[0];
        list($cells, $total_creditos) = $user->tabla_de_actividades_y_creditos_por_evento_diploma_presencial($diploma_presencial);
        $datos_acreditacion = str_replace('[creditos]', "<strong>{$total_creditos}</strong> créditos", $datos_acreditacion);
        $texto_advertencia = null;
        $fecha = null;
        if ($acreditacion_evento) {
            $texto_advertencia_array = explode(' ',  $acreditacion_evento->texto_diploma_advertencia);
            $texto_advertencia_array[0] = '';
            $texto_advertencia = implode(' ', $texto_advertencia_array);
            $fecha = $acreditacion_evento->obtener_fecha_fin_diploma('fecha');
        }
        $texto_advertencia = is_null($texto_advertencia) ? '' : '<h6 class="texto_advertencia">' . $texto_advertencia . '</h6>';
        $fecha = $fecha == '' ? date("j") . " de " . $this->meses[date("n")] . " de " . date("Y") : $fecha;

        $logo0 =  null;
        if ($acreditacion_evento) {
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
        }

        $titulo_actividades = $year > 2017 ? '' : '<h2 class="texto_actividades">ACTIVIDADES DEL PROGRAMA en las que ha participado:</h2>';

        $horas_formativas = str_replace('.00', '', $diploma_presencial->tiempo ?? 0);

        $horas_formativas = number_format($horas_formativas, 1, ',', ' ');
        
        $current = Carbon::today();
        $d = $current->day;
        $m = $this->meses[$current->month];
        $a = $current->year;

        return [
            "nombre" => $nombre,
            "header" => $header,
            "dni" => $diploma_presencial->dni ?? '',
            "cells" => $cells,
            "logos_acreditadores_multiples" => isset($logos_acreditadores_multiples) ? $logos_acreditadores_multiples : '',
            "logo_class" => isset($logo_class) ? $logo_class : '',
            "uems_class" => isset($uems_class) ? $uems_class : '',
            "seaformec_class" => isset($seaformec_class) ? $seaformec_class : '',
            "datos_acreditacion" => isset($datos_acreditacion) ? $datos_acreditacion : '',
            "texto_opcional" => isset($texto_opcional) ? $texto_opcional : '',
            "texto_advertencia" => isset($texto_advertencia) ? $texto_advertencia : '',
            "fecha" => isset($fecha) ? $fecha : '',
            "lugar" => isset($lugar) ? $lugar : '',
            "firma_identificador" => isset($firma_identificador) ? $firma_identificador : '',
            "firma_cargo" => isset($firma_cargo) ? $firma_cargo : '',
            "image_firma" => isset($image_firma) ? $image_firma : '',
            "image_logo" => isset($image_logo) ? $image_logo : '',
            "image_acreditacion" => '"' . (isset($image_acreditacion) ? $image_acreditacion : '') . '"',
            "image_cabecera" => '"' . (isset($image_cabecera) ? $image_cabecera : '') . '"',
            "titulo_actividades" => $titulo_actividades,
            "horas_formativar" => $horas_formativas,
            "entidad" => $diploma_presencial->entidad,
            "num_expediente" => $diploma_presencial->num_expediente,
            "creditos" => str_replace('.', ',', $diploma_presencial->creditos),
            'd' => $d,
            'm' => $m,
            'a' => $a,
        ];
    }


    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

    protected function renderFromView ($view, string|null $filename = null): Response
    {
        $pdf = PDF::loadHTML($view)
            ->setPaper('A4', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' >= true, 'isRemoteEnabled' => true]);

        $dompdf = $pdf->getDomPDF();

        $dompdf->setHttpContext(stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ],
        ]));

        $dompdf->render();

        return $pdf->stream($filename ?? "Diploma.pdf");
    }
    
    protected function isPresencialById (string|int $id) :bool
    {
        return strpos($id, 'P_') !== false;
    }

    private function replace ($replacements, $source)
    {
        foreach ($replacements as $key => $value) {
            $source = str_replace("{{$key}}", $value, $source);
        }

        return $source;
    }

    private function fecha($date) : string
    {
        if ($date == null)
            return "";

        $dia = date("j", strtotime($date));

        $mes = $this->meses[date("n", strtotime($date))];

        $año = date("Y", strtotime($date));

        return "{$dia} de {$mes} de {$año}";
    }

    /**
     * Método auxiliar para obtener las imágenes de organizadores o avaladores.
     *
     * @param mixed $diploma
     * @param mixed $curso
     * @param string $relacion
     * @param string $campoLogo
     * @param string $campoImagen
     * @return void
     */
    private function obtenerImagenes($diploma, $curso, $relacion, $campoLogo, $campoImagen)
    {
        $rutaWeb = config('app.url_back');
        $imagenes = [];

        if ($diploma) {
            // Si logo_organizadores/avaladores está habilitado, busca las imágenes en la relación
            if ($diploma->$campoLogo == 1 && $curso && $curso->$relacion->count() > 0) {
                foreach ($curso->$relacion as $elemento) {
                    $imagenes[] = $rutaWeb . '/storage/' . $elemento->logo;
                }
            }
            // Si no, busca las imágenes en el campo img_organizadores/img_avaladores
            elseif ($diploma->$campoLogo !== 1 && $diploma->$campoImagen) {
                $img_elementos = json_decode($diploma->$campoImagen, true);
                if (is_array($img_elementos)) {
                    foreach ($img_elementos as $img_elemento) {
                        $imagenes[] = $rutaWeb . '/storage/' . $img_elemento;
                    }
                }
            }
        }

        return $imagenes;
    }
}
