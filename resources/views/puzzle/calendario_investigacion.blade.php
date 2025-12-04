@section('estilos')

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css' />
    <link href="{{ asset('css/calendario.css') }}" rel="stylesheet">
    
@endsection

{!! $calendar->calendar() !!}

@section('scripts')

{!! $calendar->script() !!}

@append