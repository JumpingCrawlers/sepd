<?php

namespace App\Http\Controllers;

use App\Area;
use App\Pagina;
use App\Documento;
use App\MenuItem;

use Illuminate\Http\Request;

class BibliotecaController extends Controller
{

    public function index($area = null) {
        
        /* recuperar los documentos de la biblio */
        switch($area) {
            case 'eii':
                $coleccion = Documento::porArea(1); // área Enfermedad Inflamatoria Intestinal
                break;
            case 'ee':
                $coleccion = Documento::porArea(5); // área Endoscopia y Ecografía
                break;
            case 'h':
                $coleccion = Documento::porArea(6); // área Hígado
                break;
            case 'idc':
                $coleccion = Documento::porArea(3); // área Intestino Delgado y Colon
                break;
            case 'tdsmh':
                $coleccion = Documento::porArea(2); // área Tracto Digestivo Superior, Motolidad y Hemorragia
                break;
            case 'vbp':
                $coleccion = Documento::porArea(4); // área Vias Biliares y Páncreas
                break;
            case 'ct':
                $coleccion = Documento::porArea(11); // área Competencias Transversales
                break;
            case 'tis':
                $coleccion = Documento::porFormato(15); // formato Trabajos de investigación SEPD
                break;
            default:
                $coleccion = Documento::paginate(setting('site.elementos_pagina')); // por defecto todos paginados
                break;
        }
        
        /* página contenedora de la biblioteca */
        $pagina = Pagina::getPaginaBySlug('biblioteca');
        
        // gestión especial para trabajos investigación -> READONLY formato TIS activado, Videoteca SEPD desactivado
        $tis = ($area == 'tis');

        $areas = array(
            array(
                array(
                    "id" => 'todos',
                    "texto" => 'Todos los documentos'
                ),
                array(
                    "id" => 'gen',
                    "texto" => Area::where('id', 10)->first()?->nombre,
                ),
                array(
                    "id" => 'eii',
                    "texto" => Area::where('id', 1)->first()?->nombre,
                    "clase_css" => 'texto-largo'
                ),
                array(
                    "id" => 'ee',
                    "texto" => Area::where('id', 5)->first()?->nombre,
                ),
                array(
                    "id" => 'h',
                    "texto" => Area::where('id', 6)->first()?->nombre,
                ),
            ),
            array(
                array(
                    "id" => 'idc',
                    "texto" => Area::where('id', 3)->first()?->nombre,
                ),
                array(
                    "id" => 'tdsmh',
                    "texto" => Area::where('id', 2)->first()?->nombre,
                    "clase_css" => 'texto-largo'
                ),
                array(
                    "id" => 'vbp',
                    "texto" => Area::where('id', 4)->first()?->nombre,
                ),
                array(
                    "id" => 'tis',
                    "texto" => 'Trabajos de investigación SEPD',
                    "clase_css" => 'texto-largo'
                ),
            )
        );

        return view('biblioteca.index', compact('coleccion', 'pagina', 'tis', 'areas'));

    }

    /**
     * Recuperar los registros de biblioteca que cumplen unos criterios de búsqueda (recibidos en $request)
     * 
     * API
     * 
     * @param Request $request
     * @return collection Documentos
     */
    public function listaBiblioteca(Request $request)
    {
        return Documento::filtrados($request);
    }
}
