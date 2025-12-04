<?php

namespace App\Http\Controllers;

use App\Proyecto;
use App\Pagina;
use App\MenuItem;

use Illuminate\Http\Request;

class ProyectosController extends Controller
{

    protected $miga_pan = '> Proyectos';

    public function index($tipo = null)
    {
        // Valores de area_gestion o de estado, según el tipo
        $areaGestion = false;
        $estadoProyecto = false;
        $areaCalidad = "";
        switch($tipo) {
            case 'investigacion':
                $areaGestion = 'investigacionSepd';
                break;
            case 'gestion_clinica':
                $areaGestion = 'excelenciaGestion';
                $areaCalidad = 'excelenciaCalidad';
                break;
            case 'calidad':
                $areaGestion = 'excelenciaCalidad';
                break;
            case 'en_curso':
                $estadoProyecto = '1';
                break;
            case 'historicos':
                $estadoProyecto = '2';
                break;
        }
        // todos los proyectos por defecto (se filtra al cargar la página)
        $coleccion = Proyecto::paginate(setting('site.elementos_pagina'));
        
        // página contenedora de la lista de proyectos
        $pagina = Pagina::getPaginaBySlug('proyectos');

        $tis = ($tipo == 'tis');
        
        return view('proyectos.index', compact('coleccion', 'pagina', 'areaGestion', 'tis', 'estadoProyecto','areaCalidad'));

    }

    public function show(Proyecto $proyecto) {
        
        $vista = 'proyectos.show';

        // página contenedora
        $pagina = Pagina::getPaginaBySlug('proyectos-detalle');

        return view($vista)->with([
            'proyecto' => $proyecto,
            'pagina' => $pagina,
            'miga_pan' => $this->miga_pan
        ]);

    }
    
    /**
     * Recuperar los proyectos que cumplen unos criterios de búsqueda (recibidos en $request)
     * 
     * API
     * 
     * @param Request $request
     * @return collection Proyectos
     */
    public function listaProyectos(Request $request)
    {
        return Proyecto::filtrados($request);    
    }

}
