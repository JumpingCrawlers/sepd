@extends('puzzle.master')

@php $seccion = $pagina->menu->name; @endphp

@section('contenido')

    <div class="container my-4">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-xs-12">
                @include('puzzle.calendario_investigacion',['calendar' => $calendar])
            </div>
            <div class="col-lg-5 col-md-5 col-xs-12 pr-0">
                <h4 id="tituloDetalleEventos">Próximos Eventos</h4>
                
                 <div class="col-xs-12">
                    {{-- formulario de los filtros --}}
                    <form name="formFiltros" id="formFiltros" method="POST">
                        <div class="container">
                            <div class="row pl-3 py-2 mb-1 align-items-center container-buscador bg-{{ $seccion }}">
                                <div class="input-group w-100">
                                    @include('puzzle.buscador', ['tamanyo' => 'w-87', 'fondo' => 'bg-'.$seccion])
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="filtrosGet" id="filtrosGet">
                        <input type="hidden" name="paginaGet" id="paginaGet">
                    </form>
                </div>
                <div class="container px-0" id="contenidoVue">
                    <calendario-index ref="CalendarioIndex" seccion="{{ $seccion }}" evento='{{ $evento }}'>
                    </calendario-index>
                </div>
            </div>
        </div>
    </div>
   
@endsection

@section('scripts')

    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/filtros.js') }}"></script>
    <script src="{{ asset('js/calendarioListado.js') }}"></script>
    <script>
        window.origen = 'calendarioInvestigacion';
    </script>
    <script>
        // Guarda la función original
        const originalAxiosGet = axios.get;

        // Sobrescribe axios.get
        axios.get = function(url, config) {
            if (typeof url === 'string' && url.includes('/api/calendario')) {
                const separator = url.includes('?') ? '&' : '?';
                url += `${separator}origen=calendarioInvestigacion`;
            }
            return originalAxiosGet.call(this, url, config);
        };
    </script>






@append