{{-- 3 Ways - Alexis Bogado --}}
@extends('base.sepd')

@section('content')
    <div class="container">
        <div class="col-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">Diploma</h2>
            </div>

            <div class="pointer mb-4 px-0 pb-3">
                <div class="row">
                    <a href="{{ route('validar.diploma') }}">
                        <button class="btn btn-primary">< Volver</button>
                    </a>
                    <div class="mt-3">
                        <h3>El usuario {{ $diploma->usuario->nombre_completo }} ha completado el {{$evento}} <strong>"{{ $diploma->curso->titulo }}"</strong> satisfactoriamente en la fecha <?php echo $diploma->created_at->format('d')?> de <?php echo $meses[intval($diploma->created_at->format('m'))];?> de <?php echo $diploma->created_at->format('Y')?>.</h3>
                        <br>
                        Codigo de diploma: <b>{{ $diploma->id }}</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection