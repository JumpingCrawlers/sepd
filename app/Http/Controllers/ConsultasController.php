<?php

namespace App\Http\Controllers;

use App\Consulta;
use App\Pagina;

use Illuminate\Http\Request;
// añadido para enviar el email de contacto (mail y validator)
use App\Mail\Consulta as MailConsulta;
use Illuminate\Support\Facades\Mail;
// Validar los datos de la consulta nueva
use Illuminate\Support\Facades\Validator;
// Guardar el id de usuario si estaba conectado
use Auth;

class ConsultasController extends Controller
{

    protected $miga_pan = '> Consultas';

    public function index($tipo = null) {
        
        // Valores de area_gestion, según el tipo
        switch($tipo) {
            case 'investigacion':
            case 'clinica':
            case 'calidad':
                $areagestion = $tipo;
                break;
            default:
                $areagestion = false;
        }
        // todas las consultas por defecto (se filtra al cargar la página)
        $coleccion = Consulta::paginate(setting('site.elementos_pagina'));
        
        // página contenedora de la lista de proyectos
        $pagina = Pagina::getPaginaBySlug('consultas');
        
        return view('consultas.index', compact('coleccion', 'pagina', 'areagestion'));

    }

    public function show(Consulta $consulta) {
        
        $vista = 'consultas.show';

        // página contenedora
        $pagina = Pagina::getPaginaBySlug('consultas-detalle');

        return view($vista)->with([
            'consulta' => $consulta,
            'pagina' => $pagina,
            'miga_pan' => $this->miga_pan
        ]);

    }

    // validar datos de contacto
    protected function validator(array $data) {

        return Validator::make($data, [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'centro' => 'required|string|min:5',
            'area_gestion' => 'required|in:investigacion,calidad,clinica',
            'consulta' => 'required|string|min:6',
            'g-recaptcha-response' => 'required|captcha'
        ]);        

    }

    // validar datos nueva consulta, crearla y enviar Email con comprobante
    public function store(Request $request) {

        // validar los datos de formulario de consulta
        $validator = $this->validator($request->all());
        $validator->validate();

        // crear la consulta
        $nuevo_registro = array();
        // modificar los campos necesarios
        $nuevo_registro['titulo'] = (strlen($request->consulta) > 100 ) ? substr($request->consulta, 0, 100)."..." : $request->consulta;
        $nuevo_registro['publicado'] = 0;
        $nuevo_registro['id_usuario'] = (Auth::user()) ? Auth::user()->id : 0;
        $nuevo_registro['respuesta'] = "";
        // y fusionarlo con el request
        $nuevo_registro = array_merge($nuevo_registro, $request->except(['aceptacion', 'g-recaptcha-response']));

        $consulta = Consulta::create($nuevo_registro);
        
        // Enviar el mensaje con copia al usuario y a sepd@sepd.es
        Mail::to($request->email)
                ->bcc(setting('site.email_contacto'))
                ->queue(new MailConsulta(array(
                        'nombre' => $request->nombre,
                        'centro' => $request->centro,
                        'descripcion_area_gestion' => $consulta->descripcion_area_gestion,
                        'asunto' => $request->asunto,
                        'consulta' => $request->consulta,
                )));


        $pagina = Pagina::getPaginaBySlug('consultas');
        $enviado = true;
        $areagestion = false;
        
        return view('consultas.index', compact('pagina', 'enviado', 'areagestion'));

    }
    
    /**
     * Recuperar las consultas que cumplen unos criterios de búsqueda (recibidos en $request)
     * 
     * API
     * 
     * @param Request $request
     * @return collection Consultas
     */
    public function listaConsultas(Request $request) {

        return Consulta::filtrados($request);
        
    }

}
