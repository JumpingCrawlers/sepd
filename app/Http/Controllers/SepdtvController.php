<?php

namespace App\Http\Controllers;

use App\Area;
use App\Pagina;
use App\Sepdtv;
use App\MenuItem;

use Illuminate\Http\Request;

class SepdtvController extends Controller
{
    public function index($area = null) {
        
        /* recuperar los videos según el área */
        switch($area) {
            case 'eii':
                $coleccion = Sepdtv::porArea(1); // área Enfermedad Inflamatoria Intestinal
                break;
            case 'ee':
                $coleccion = Sepdtv::porArea(5); // área Endoscopia y Ecografía
                break;
            case 'h':
                $coleccion = Sepdtv::porArea(6); // área Hígado
                break;
            case 'idc':
                $coleccion = Sepdtv::porArea(3); // área Intestino delgado y colon
                break;
            case 'tdsmh':
                $coleccion = Sepdtv::porArea(2); // área Tracto digestivo superior
                break;
            case 'vbp':
                $coleccion = Sepdtv::porArea(4); // área Vias biliares y Páncreas
                break;
            case 'esp':
                $coleccion = Sepdtv::porArea(12); // área Especialidad
                break;
            case 'gen':
                $coleccion = Sepdtv::porArea(10); // área General
                break;
            case 'video':
                return abort(404);
            break;
            default:
                $coleccion = Sepdtv::all(); // por defecto todos
                break;
        }
        
        /* página contenedora */
        $pagina = Pagina::getPaginaBySlug('sepdtv');

        $areas = $this->getAreas();

        return view('sepdtv.index', compact('coleccion', 'pagina', 'areas'));

    }

    public function showVideo($id)
    {
        $video = Sepdtv::findOrFail($id);
        
        $pagina = Pagina::getPaginaBySlug('sepdtv');

        $areas = $this->getAreas();

        return view('sepdtv.index', compact('video', 'pagina', 'areas'));
    }

    protected function getAreas ()
    {
        return array(
            array(
                array(
                    "id" => 'todos',
                    "texto" => 'Todos los vídeos'
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
                    "id" => 'esp',
                    "texto" => 'Especialidad'
                ),
            )
        );
    }

    /**
     * Recuperar los registros de TV que cumplen unos criterios de búsqueda (recibidos en $request)
     * 
     * API
     * 
     * @param Request $request
     * @return collection Tv
     */
    public function listaSepdtv(Request $request) {

        return Sepdtv::filtrados($request);

    }
    
    /**
     * Suma una reproducción al vídeo
     * 
     * API
     * 
     * @param Sepdtv $video (por PK codigo, automatic binding)
     * @return empty
     */
    public function sumaReproduccion(Sepdtv $video) {

        $video->contador++;
        $video->save();
        
        return 'ok';

    }
    
}
