{{-- 3 Ways - Alexis Bogado --}}
@extends('base.sepd')

@section('content')
    <div class="container">
        <div class="col-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">Pruebas APP</h2>
            </div>

            <div class="pointer mb-4 px-0 pb-3">
                <div class="row">
                    <div class="col-12 my-3" style="word-break:break-all">
                        Datos encriptados:<br>
                        {{ urlencode($encrypted) }}
                    </div>
                    <div class="col-12 my-3">
                        <a href="https://posgrado.sepd.es/loginin/?token={{ urlencode($encrypted) }}" target="_blank" class="btn btn-primary btn-block" style="font-size:15pt;">Acceder</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection