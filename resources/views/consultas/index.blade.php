@extends('puzzle.master')

@section('slider')

    @include('puzzle.slider')

@endsection

@section('contenido')
<div class="container px-0 mb-3">
    <div class="row">
        <div class="col-sm-4 col-lg-3">

            <form name="formFiltros" id="formFiltros" method="POST">

                <div class="container">
                    <div class="row pl-3 py-3 mb-2 align-items-center container-buscador bg-cid">
                        <div class="input-group w-100">
                            @include('puzzle.buscador', ['tamanyo' => 'w-87', 'fondo' => 'bg-cid'])
                        </div>
                    </div>
                </div>

                <div class="container mb-2">
                    @include('puzzle.filtros.consultas_gestion', ['areagestion' => $areagestion])
                </div>

                <input type="hidden" name="filtrosGet" id="filtrosGet">
                <input type="hidden" name="paginaGet" id="paginaGet">
            </form>

            {{-- loop de las plantillas de la página, en este caso los valores de ancho y posición no se usan --}}
            @if ($pagina->contenido_extra > 0)
                @foreach ($pagina->pastillas_contenido as $pastilla)
                    <div class="mt-3">

                        @php $margen_inf = ''; @endphp
                        @include('paginas.pastilla')

                    </div>
                @endforeach
            @endif

        </div>
        <div class="col-sm-8 col-lg-9 pl-4">
            <div class="container pl-0" id="contenidoVue">
                
                <consultas-index ref="ConsultasIndex" url-web-antigua='{{ setting('site.url_web_antigua') }}'>
                    
                </consultas-index>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8 offset-sm-4 col-lg-9 offset-lg-3 pl-4">
            <div class="contenido-titulo-pagina color-cid">Envía una consulta</div>
            <hr class="borde-cid">
            @if (isset($enviado) && $enviado)
                <p class="alert alert-success">Tu consulta ha sido enviada correctamente. En la dirección de correo que has introducido recibirás una copia como confirmación.</p>
                <a href="{{ route('consultas') }}" class="btn"{{ getHtmlEstiloBoton('', '') }}>Quiero enviar otra consulta</a>
            @else
                @include('consultas.form_alta_consulta')
            @endif            
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- JS general, vue + filtros --}}
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/filtros.js') }}"></script>
    {{-- JS específico para consultas --}}
    <script src="{{ asset('js/consultas.js') }}"></script>
    {{-- Captcha para el formulario de enviar consulta --}}
    <script src='https://www.google.com/recaptcha/api.js'></script>
    {!! NoCaptcha::renderJs('es', true, 'recaptchaCallback') !!}

@endsection
