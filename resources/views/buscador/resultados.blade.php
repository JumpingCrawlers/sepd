@extends('puzzle.master')

@section('estilos')
    <style>
        .page-link {
            color: #040b54;
        }
        .page-item.active .page-link {
            background-color: #040b54;
            border-color: #040b54;
        }
    </style>
    <style>
        .resultado-buscador * {
            color: #040b54;
        }
        .btn-categories {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .btn-category {
            position: relative;
            height: 20px;
            box-sizing: border-box;
            padding: 1px 12px 1px 12px;
            background-color: #040b54;
            color: #fff;
            border-radius: 9999px;
            font-weight: 500;
            cursor: pointer;
        }
        .btn-search-category {
            height: auto;
            padding: 5px 15px;
            background: gray;
        }
        .btn-search-active {
            background: #ffa52b;
        }
        .descripcion{
            font-size: 12px;
        }
    </style>
@endsection

@section('contenido-detalle')

<div class="container mt-2 mb-3 resultado-buscador">

    <div class="row py-3 mb-2 align-items-center bg-institucional">
        @php
            $requestSearch = app('request')->input('search');
        @endphp
        <div class="pl-3 input-group w-100">
            <div class="col-md-9 col-xs-12">
                <div readonly="readonly" class="bg-institucional w-100 border-0 border-bottom text-white">
                    Mostrando resultados {{ $buscador->perPage()*($buscador->currentPage()-1)+1 }} al {{ min($buscador->perPage()*$buscador->currentPage(), $buscador->total()) }} de {{ $buscador->total() }}
                    @if (trim($requestSearch))
                        para <strong style="color: #FFF">{{ $requestSearch }}</strong>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {{-- @if ($buscador->total() > 0)
        <div id="btn-volver-noticia" class="col-12 W-100 text-right"><a href="javascript:history.back()">Volver</a></div>
    @endif --}}

    <form action="{{ url()->current() }}?search={{ app('request')->input('search') }}" method="GET">
        <input type="hidden" name="search" value="{{ app('request')->input('search') }}">
        <div class="input-group w-100 btn-categories">
            @foreach ($search_categories as $category)
                <label for="{{ $category->category }}" @class([
                    "btn-category btn-search-category",
                    'btn-search-active' => $category->category == app('request')->input('search_category')
                ])>
                    <input type="radio" id="{{ $category->category }}" name="search_category" value="{{ $category->category }}" style="display: none" @if($category->category == app('request')->input('search_category')) checked @endif>
                    {{ $category->category }} ({{ $category->total }})
                </label>
            @endforeach
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all radio buttons with name="category"
            const categoryRadios = document.querySelectorAll('input[name="search_category"]');
            
            // Add event listener to each radio button
            categoryRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    // Submit the form when a radio button is selected
                    this.closest('form').submit();
                });
            });
        });
    </script>

    <div>
        @foreach ($buscador as $key => $result)
            <!-- El cuerpo de la noticia -->
            <div class="px-0 resultado-buscador">
                <div class="row mb-3">
                    <div class="col-12 callout {!! $result->slug !!} flex-row w-100">
                        <a href="{{ $result->url }}" class="align-items-start">
                            {{ $result->title }}
                            <br>
                            @php 
                                $palabras= explode(' ', strip_tags($result->description));

                                if(count($palabras)>30){
                                    $texto_limitado = implode(' ', array_slice($palabras, 0, 30))." ...";
                                }else{
                                    $texto_limitado = strip_tags($result->description);
                                }
                                
                            @endphp
                            <p class="descripcion">{{ html_entity_decode($texto_limitado) }}</p>
                            <br>
                            <strong>{{ $result->category }}</strong>
                            <br>
                            <small style="opacity: .6">
                                {{ $result->created_formater }}
                            </small>
                        </a>
                    </div>
                </div>
            </div>
            <hr />
        @endforeach
    </div>

    @include('paginacion.arrays', ['paginator' => $buscador])

</div>

@endsection


@section('contenido')

    @include('puzzle.contenido')

@endsection
