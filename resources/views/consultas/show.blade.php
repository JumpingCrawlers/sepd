@extends('puzzle.master')

{{-- Consultas podría tener slider, depende de "tipo_slider" --}}
@section('slider')

    @include('puzzle.slider')

@endsection

{{-- seccion = nombre del menú --}}
@php $seccion = $pagina->menu->name @endphp

@section('contenido-detalle')
    <div class="container">
    <div class="row py-3 mb-2 align-items-center bg-{{ $seccion }}">
        <div class="pl-3 input-group w-100">
            <div id="cabecera-consulta" type="text" class="bg-{{ $seccion }} w-100 border-0 border-bottom text-white">{!! $consulta->titulo !!}</div>
        </div>
    </div>

    <!-- El cuerpo de la consulta -->
    <div class="px-0">
        <div class="row mb-3">
            <div class="col-12 callout {{ $seccion }} flex-row w-100">
                <div class="d-flex flex-column align-items-start">
                    <div class="container">
                        <div class="row pt-3">
                            <div class="col-12 pr-0">
                                <div class="w-100 text-right">
                                    <a href="javascript:history.back()">Volver</a>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-12 pr-0">
                                <div class="mb-2 text-justify">
                                    <p class="color-cid"><strong>Consulta:</strong></p>
                                    {!! $consulta->consulta !!}
                                </div>
                                <div class="mb-2 text-justify">
                                    <p class="color-cid"><strong>Respuesta:</strong></p>
                                    {!! $consulta->respuesta !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('contenido')
    @include('puzzle.contenido')
@endsection
