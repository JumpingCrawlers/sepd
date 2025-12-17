{{-- Primero, comprobar el tipo de slider --}}
@if ($pagina->tipo_slider <> '0')

<div class="container px-0 mb-3">
    <div class="row">

    {{-- slider dividido o no --}}
    @switch ($pagina->tipo_slider)
        @case('2s+1i')
        @case('2sA+1iA')
            <div class="col-sm-8 col-slider-con-imagen">
                @php
                    // variable para la clase CSS del carousel
                    ($pagina->tipo_slider == '2s+1i') ? $formato_carousel = '-2x1' : $formato_carousel = '-2x1A';
                @endphp
                @include('paginas.slider')
            </div>
            <div class="col-sm-4 col-imagen-en-slider">
                @include('paginas.imagen-slider')
            </div>
            @break
        @case('3s+1i')
            <div class="col-sm-9 col-slider-con-imagen">
                @php
                    // variable para la clase CSS del carousel
                    $formato_carousel = '-3x1';
                @endphp
                @include('paginas.slider')
            </div>
            <div class="col-sm-3 col-imagen-en-slider">
                @include('paginas.imagen-slider')
            </div>
            @break
        @case('3sA')
        @case('3s')
            <div class="col">
                @php
                    // variable para la clase CSS del carousel
                    ($pagina->tipo_slider == '3sA') ? $formato_carousel = '-A' : $formato_carousel = '';
                @endphp
                @include('paginas.slider')
            </div>
            @break
        @case('3i')
            <div class="col" style="overflow:hidden;">
                @include('paginas.imagen-slider')
            </div>
            @break

    @endswitch

    </div>
</div>

@endif

