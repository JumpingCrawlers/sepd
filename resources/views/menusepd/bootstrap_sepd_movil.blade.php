
@foreach ($items as $item)
    
    {{-- Comprobar si es parte del camino "activo" --}}
    @php
        if (strpos($options->listaIdsActivos, '-'.$item->id.'-') !== false) {
            $caminoOpcionActiva = true;
        } else {
            $caminoOpcionActiva = false;
        }
    @endphp

    {{-- En MOVIL, no hay lista, es solo un elemento collapse --}}
    {{-- Tampoco hay tratamiento especial si es inner loop o no --}}
    @if($item->children->isEmpty())
        {{-- Si NO TIENE hijos, comprobar URL directamente y meter link --}}
        <a class="nav-link @if(url()->current()==url($item->link())) menu-movil-activo @endif" 
           href="{{ url($item->link()) }}" target="{{ $item->target }}">
            {{ $item->title }}
        </a>
    @else
        {{-- Si TIENE hijos, comprobar ListaIdsActivos --}}
        <a class="nav-link @if($caminoOpcionActiva) menu-movil-activo @endif"
            href="#submenu{{ $item->id }}" data-toggle="collapse" aria-expanded="false" aria-controls="submenu{{ $item->id }}">
            <div class="d-inline-block">
                {{ $item->title }}
                &nbsp;<span class="flecha {{ $item->menu()->first()->name }} float-right mt-2"></span>
            </div>
        </a>
        <div class="collapse ml-4 border-left borde-{{ $item->menu()->first()->name }}" id="submenu{{ $item->id }}">
            @include('menusepd.bootstrap_sepd_movil', ['items' => $item->children, 'options' => $options])
        </div>
    @endif

@endforeach
