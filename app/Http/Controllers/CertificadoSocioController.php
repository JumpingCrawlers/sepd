<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\CertificadosSocio;
use Carbon\Carbon;

class CertificadoSocioController extends Controller
{
    public function download (string $type, int $model_id)
    {
        try {
            $user = auth()->user();
    
            $replaceArray = [
                '[Dr/a.]' => $user->tratamiento,
                '[nombre_socio]' => $user->nombre . ' ' .$user->apellidos,
                '[nif_socio]' => $user->dni,
                '[fecha_actual]' => Carbon::now()->translatedFormat('d F \d\e\l Y'),
                '[responsable/miembro]' => '[responsable/miembro]',
                '[nombre_comité antiguo/nuevo]' => '[nombre_comité antiguo/nuevo]',
            ];
            
            $filename = null;
            Carbon::setLocale('es');
            switch ($type) {
                case 'cargo':
                        $cargo = $user->cargos()->where('id', $model_id)->first();
                        
                        $certificados_socio = CertificadosSocio::where('template', 'modelo-certificado-cargo')->first();

                        $replaceArray['[nombre_comite antiguo/nuevo]'] = $cargo->cargo->nombre_cargoi;

                        if ($cargo->cargo->nombre_cargoi == 'Junta Directiva SEPD') {
                            $replaceArray['[responsable/miembro]'] = 'Presidente';
                        } else {
                            $replaceArray['[responsable/miembro]'] = $cargo->responsable ? 'Responsable' : 'Miembro';
                        }

                        if ($cargo->fecha_fin)
                            $replaceArray['[fecha]'] = 'desde ' . Carbon::parse($cargo->fecha_inicio)->translatedFormat('F \d\e\l Y') . ' hasta ' . Carbon::parse($cargo->fecha_fin)->translatedFormat('F \d\e\l Y');
                        else 
                            $replaceArray['[fecha]'] = 'desde ' . Carbon::parse($cargo->fecha_inicio)->translatedFormat('F \d\e\l Y');
                        
                        $filename = 'cargo-'  . Str::slug($cargo->cargo->nombre_cargoi);
                        
                    break;
                case 'reconocimiento':

                    $reconocimiento = $user->socio->reconocimientos()->where('id', $model_id)->first();

                    $certificados_socio = CertificadosSocio::where('template', 'modelo-certificado-reconocimiento')->first();
                    
                    $replaceArray['[RECONOCIMIENTO]'] = $reconocimiento->tipo_reconocimiento->nombre_reconocimiento;
                    
                    $replaceArray['[TEXTO_DEL_TIPO_RECONOCIMIENTO]'] = $certificados_socio->text_type_reconocimiento;

                    $filename = 'reconocimiento-'  . Str::slug($replaceArray['[RECONOCIMIENTO]']);
                    
                    break;
                default:
                    return abort(404);
                    break;
            }
    
            $replaceArray['[tratamiento_secreatario]'] = $certificados_socio->secretario_tratamiento;
                        
            $replaceArray['[nombre_secretario]'] = $certificados_socio->secretario_nombre;

            $replaceArray['[nif_secretario]'] = $certificados_socio->secretario_nif;

            $replaceArray['[firma_secretario]'] = '<img src="'. asset('storage/' . $certificados_socio->secretario_firma) . '" width="300px">';

            return $this->downloadCertificadoSocio($certificados_socio, $replaceArray, $filename);
        } catch (\Throwable $th) {
            return abort(500);
        }
    }

    public function downloadCertificadoSocio (CertificadosSocio $certificados_socio, array $replaceArray, string|null $filename = null)
    {
        $search = array_keys($replaceArray);

        $replace = array_values($replaceArray);
        
        $content = str_replace($search, $replace, $certificados_socio->content);

        $view = view('certificados-socios/modelos-certificados/' . $certificados_socio->template, [
            'content' => $content,
            'firma' =>  null
        ]);

        $pdf = PDF::loadHTML($view)->setOptions(['isHtml5ParserEnabled' >= true, 'isRemoteEnabled' => true,'defaultFont' => 'Archivo','chroot' => realpath(public_path()),]);

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

        return $pdf->stream($filename ? "{$filename}.pdf" : "{$certificados_socio->template}.pdf");
    }
}
