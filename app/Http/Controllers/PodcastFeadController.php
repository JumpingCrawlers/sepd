<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PodcastFead;
use App\Pagina;

class PodcastFeadController extends Controller
{
	public function index ()
	{
        $pagina = Pagina::getPaginaBySlug('escucha--sepdigestiva');

		return view('podcast-fead.index',  [
			'pagina' => $pagina,
		]);
	}

	public function listaPodcastFeads (Request $request)
	{
        return PodcastFead::filtrados($request);
    }
    
}
