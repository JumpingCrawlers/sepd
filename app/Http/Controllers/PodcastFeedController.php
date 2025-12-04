<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PodcastFeed;
use App\Pagina;

class PodcastFeedController extends Controller
{
	public function index ()
	{
        $pagina = Pagina::getPaginaBySlug('noticias');

		return view('podcast-feed.index',  [
			'pagina' => $pagina,
		]);
	}

	public function listaPodcastFeeds (Request $request)
	{
        return PodcastFeed::filtrados($request);
    }
    
}
