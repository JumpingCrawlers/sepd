@extends('puzzle.master')

{{-- Todas las páginas pueden tener slider, depende de "tipo_slider" --}}
@section('slider')

    @include('puzzle.slider')

@endsection

{{-- Del mismo modo, todas las paginas pueden tener pastillas, depende de 
     si tienen pastillas asignadas (se muestran en 2 ó 3 columnas.    --}}

@section('pastillas')

    @include('puzzle.pastillas')

@endsection

{{-- También con los destacados --}}

@section('destacados')

    @include('puzzle.destacados')

@endsection

@section('contenido-detalle')

    <div class="contenido-titulo-seccion borde-institucional">
        <span class="bg-institucional">Mapa web</span>
    </div>

    <div class="container mb-4">
        <div class="row">
            <div class="col-12">
                <div class="row bg-institucional p-2 pl-3 mb-3 text-white">
                    Institucional
                </div>
                <div class="row">
                    {!! menu('institucional', 'menusepd.mapa', ['nivel' => 0, 'seccion' => 'institucional']) !!}
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="row bg-formacion p-2 pl-3 mb-3 text-white">
                    Formación
                </div>
                <div class="row">
                    {!! menu('formacion', 'menusepd.mapa', ['nivel' => 0, 'seccion' => 'formacion']) !!}
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="row bg-cid p-2 pl-3 mb-3 text-white">
                    Investigación
                </div>
                <div class="row">
                    {!! menu('cid', 'menusepd.mapa', ['nivel' => 0, 'seccion' => 'cid']) !!}
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="row bg-cid p-2 pl-3 mb-3 text-white">
                    Gestión y Calidad
                </div>
                <div class="row">
                    {!! menu('gyc', 'menusepd.mapa', ['nivel' => 0, 'seccion' => 'gyc']) !!}
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="row bg-congreso p-2 pl-3 mb-3 text-white">
                    Congreso SEPD
                </div>
                <div class="row">
                    {!! menu('congreso', 'menusepd.mapa', ['nivel' => 0, 'seccion' => 'congreso']) !!}
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="row bg-publicaciones p-2 pl-3 mb-3 text-white">
                    Publicaciones
                </div>
                <div class="row">
                    {!! menu('publicaciones', 'menusepd.mapa', ['nivel' => 0, 'seccion' => 'publicaciones']) !!}
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="row bg-prensa p-2 pl-3 mb-3 text-white">
                    Comunicación
                </div>
                <div class="row">
                    {!! menu('comunicacion', 'menusepd.mapa', ['nivel' => 0, 'seccion' => 'comunicacion']) !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('contenido')

    @include('puzzle.contenido')

@endsection
