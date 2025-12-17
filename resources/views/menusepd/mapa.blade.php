
@foreach ($items as $item)

    @if($options->nivel == 0)
    <div class='col-sm-auto mb-2'>
    @else
    
    <li>
    @endif

    @if($item->link() != '')
    <a href="{{ url($item->link()) }}" target="{{ $item->target }}">
    @endif
        <span @if($options->nivel == 0) class="font-weight-bold" @endif>{{ $item->title }}</span>
    @if($item->link() != '')
    </a>
    @endif
    @if(!$item->children->isEmpty())
        <ul @if($options->nivel == 0) class="mapa-web borde-{{ $options->seccion }}" @endif>
        @php 
            $options_next = clone $options;
            $options_next->nivel++;
        @endphp
        @include('menusepd.mapa', ['items' => $item->children, 'options' => $options_next])
        </ul>
    @endif

    @if($options->nivel == 0)
    </div>
    @else
    </li>
    @endif

@endforeach

