<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PodcastFead extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'enlace',
        'publication_date',
        'image',
        'podcast_fead_type_id',
        'description'
    ];

    /**
     * Lista de noticias filtradas según parámetros del request
     * @return collection Noticias
     */
    public static function filtrados($request) {

        // Montamos la query sin filtros
        $query = PodcastFead::query();
        
        // Recorrer la lista de parámetros y montar la query
        $anyos = array();
        $tipo = null;
        foreach ($request->all() as $filtro => $valor) {
            $variable = preg_split('/_/',$filtro);
            switch (strstr($filtro, "_", true)) {
                case "texto":
                    $query = $query->where(function ($query) use ($valor) {
                                    $query->where('title', 'like', '%' . $valor . '%')
                                          ->orWhere('description', 'like', '%' . $valor . '%');
                             });
                    break;
                case "tipo":
                    $tipos[] = $variable[1];
                case "years":
                case "anyos":
                    $anyos[] = $variable[1];
                    break;
                default:
                    break;
         
            }
        }

        if ($request->tipo_contenido) {
            $tipo = \App\PodcastFeadType::where('id', $request->tipo_contenido)->first();
            $query = $query->where('podcast_fead_type_id', $request->tipo_contenido); 
        }

        if (!empty($anyos)) {
            $lista_anyos = implode("','", $anyos);

            $query = $query->whereRaw("YEAR(publication_date) IN ('".$lista_anyos."')");
        }

        $query->orderBy('publication_date', 'desc');
        
        return response()->json([
            'podcasts' => $query->paginate(setting('site.elementos_pagina')),
            'tipo' => $tipo
        ]);

    }

    /**
     * Diferentes años con curso
     * @return collection Años
     */
    public static function anyos() {

        // alias de las columnas para homogeneizar filtros
        return self::withoutGlobalScopes()
                ->selectRaw('DISTINCT YEAR(publication_date) as id, YEAR(publication_date) as nombre')
                // ->where('estado', 1)
                ->where('publication_date', '>', 0)
                ->orderBy('publication_date', 'desc')
                ->get();
    }

    /**
     * @return collection types
     */
    public static function tipos () {

        // alias de las columnas para homogeneizar filtros
        return PodcastFeadType::orderBy('id', 'desc')->get();
    }

    /**
     * 
     * @return \App\PodcastFeadType
     */
    public function podcast_feed_type (): BelongsTo
    {
        return $this->belongsTo(PodcastFeadType::class, 'podcast_fead_type_id');
    }
}
