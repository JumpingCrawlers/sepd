<input 
    type="text" 
    class="buscador @if(isset($fondo)) {{ $fondo }} @endif @if(isset($tamanyo)) {{ $tamanyo }} @endif" 
    name="search" 
    placeholder="Buscar...."
    value="{{ app('request')->input('search')}}"
>
<button type="submit" class="buscador"></button>
