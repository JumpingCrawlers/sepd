@extends('puzzle.master')

{{-- NOTICIAS puede tener slider, depende de "tipo_slider" --}}
@section('slider')

    @include('puzzle.slider')

@endsection

@section('contenido-detalle')
    

    <!-- El cuerpo de la noticia -->
    <div class="revistas-index">
        <div class="row mb-3">
            <div class="col-12  w-100">
                <strong>Número {!! $revista->numero!!}</strong> - <em>{!! $revista->descripcion !!}</em>
                <a href="javascript:window.history.back()" class="float-right">Volver</a>
                <iframe border="0" src="{!! $revista->archivo !!}" class="w-100" scrolling="auto"></iframe>
            </div>
        </div>
    </div>
</div>

@endsection

@section('contenido')
    @include('puzzle.contenido')
@endsection

@section('scripts')
<script>
/*  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  } */
</script>
@endsection