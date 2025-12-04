<?php

namespace App\Http\Controllers;

//use App\Pagina;
use App\Calendario;
use App\Pagina;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{    
    protected $miga_pan = '> Calendario de eventos';

    public function index($evento = null) {
      
        /* página contenedora */
        $pagina = Pagina::getPaginaBySlug('calendario');

        return view('calendario.index')->with([
            'pagina' => $pagina,
            'miga_pan' => $this->miga_pan,
            'evento' => $evento
        ]);

    }
    
    public function indexInvestigacion($evento = null) {
        
        /* página contenedora */
        $pagina = Pagina::getPaginaBySlug('calendario-investigacion');
        $calendar = Calendario::calendarioInvestigacion();
        return view('calendario.index_investigacion')->with([
            'pagina' => $pagina,
            'miga_pan' => $this->miga_pan,
            'evento' => $evento,
            'calendar' => $calendar,
        ]);

    }
     /**
     * Recuperar los registros de prensa que cumplen unos criterios de búsqueda (recibidos en $request)
     * 
     * API
     * 
     * @param Request $request
     * @return collection Prensa
     */
    public function listaEventos(Request $request) {
        $origen = $request->get('origen');
        
        if ($origen === 'calendarioInvestigacion') {
            return Calendario::filtradosInvestigacion($request);
        }else{
            return Calendario::filtrados($request);
        }
        
    }
    
    
}
