
@foreach ($items as $item)
    
    {{-- Comprobar si es parte del camino "activo" --}}
    @php
        if (strpos($options->listaIdsActivos, '-'.$item->id.'-') !== false) {
            $caminoOpcionActiva = true;
        } else {
            $caminoOpcionActiva = false;
        }
    @endphp

    @if(!isset($innerLoop))
        {{-- Si no estamos en un loop interior => primer nivel --}}
        @if($item->children->isEmpty())
            {{-- Si NO TIENE hijos, comprobar URL directamente --}}
            <li class="nav-link @if(url()->current()==url($item->link())) activo bg-{{ $item->menu()->first()->name }} @endif">
        @else
            {{-- Si TIENE hijos, comprobar ListaIdsActivos --}}
            <li class="nav-link position-relative @if($caminoOpcionActiva) activo bg-{{ $item->menu()->first()->name }} @endif">
        @endif
    @else
        @if($item->children->isEmpty())
            <li class="dropdown-item @if($caminoOpcionActiva) activo @endif">
        @else
            <li class="dropdown-submenu @if($caminoOpcionActiva) activo @endif">
        @endif
    @endif


    @php
    
        $listItemClass = null;
        $linkAttributes =  null;
        $styles = null;
        $icon = null;
        $caret = null;

        // Background Color or Color
        if (isset($options->color) && $options->color == true) {
            $styles = 'color:'.$item->color;
        }
        if (isset($options->background) && $options->background == true) {
            $styles = 'background-color:'.$item->color;
        }

        // With Children Attributes
        if(!$item->children->isEmpty()) {
//            $linkAttributes =  'class="dropdown-toggle" data-toggle="dropdown"';
            $linkAttributes =  'dropdown-toggle" data-toggle="dropdown';
            $caret = '<span class="caret"></span>';

            if(url($item->link()) == url()->current()){
                $listItemClass = 'dropdown active';
            }else{
                $listItemClass = 'dropdown';
            }
        }

        // Set Icon
        if(isset($options->icon) && $options->icon == true){
            $icon = '<i class="' . $item->icon_class . '"></i>';
        }
        
    @endphp

        <a class="@if(!isset($innerLoop)) nav-link @else dropdown-item @endif {!! $linkAttributes ?? '' !!}" href="{{ url($item->link()) }}" target="{{ $item->target }}" style="{{ $styles }}">
            {!! $icon !!}
            {{ $item->title }}
            {!! $caret !!}
        </a>

        @if(!$item->children->isEmpty())
            <ul class="dropdown-menu" role="menu" aria-labelledby="navbarDropdown">
            @include('menusepd.bootstrap_sepd', ['items' => $item->children, 'options' => $options, 'innerLoop' => true])
            </ul>
        @endif

    </li>

@endforeach
