{{-- ordenar Twitter/YouTube/LinkedIn/Facebook --}}
{{-- guardar en un array por orden --}}
@foreach ($redes_sociales as $red)
    @php
        $elemento = '<a href="'.$red->enlace.'" target="_blank"><img src="'.Voyager::image($red->imagen).'" width="25" height="25" border="0"></a>';
        switch(strtoupper($red->nombre)) {
            case "TWITTER":
                $rrss_ordenado[0] = $elemento;
                break;
            case "YOUTUBE":
                $rrss_ordenado[1] = $elemento;
                break;
            case "LINKEDIN":
                $rrss_ordenado[2] = $elemento;
                break;
            case "FACEBOOK":
                $rrss_ordenado[3] = $elemento;
                break;
            case "INSTAGRAM":
                $rrss_ordenado[4] = $elemento;
                break;
            case "BLUESKY":
                $rrss_ordenado[5] = $elemento;
                break;    
        }
    @endphp
@endforeach

@php ksort($rrss_ordenado) @endphp
@foreach ($rrss_ordenado as $red)
    {!! $red !!}
@endforeach
