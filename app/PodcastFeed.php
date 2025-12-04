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

        foreach ($request->all() as $filtro => $valor) {
            $variable = preg_split('/_/',$filtro);
            switch (strstr($filtro, "_", true)) {
                case "texto":
                    $query = $query->where(function ($query) use ($valor) {
                                    $query->where('title', 'like', '%' . $valor . '%')
                                          ->orWhere('description', 'like', '%' . $valor . '%');
                             });
                    break;
                case "anyos":
                    $anyos[] = $variable[1];
                    break;
                default:
                    break;
         
            }
        }
        
        if (!empty($anyos)) {
            $lista_anyos = implode("','", $anyos);

            $query = $query->whereRaw("YEAR(publication_date) IN ('".$lista_anyos."')");
        }

        return $query->paginate(setting('site.elementos_pagina'));

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
                ->orderBy('id', 'desc')
                ->get();
    }

    /**
     * 
     * @return \App\PodcastFeedType
     */
    public function podcast_feed_type (): BelongsTo
    {
        return $this->belongsTo(PodcastFeedType::class, 'podcast_feed_type_id');
    }
}
