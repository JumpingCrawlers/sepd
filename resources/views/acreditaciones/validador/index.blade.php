{{-- 3 Ways - Alexis Bogado --}}
@extends('base.sepd')

@section('content')
    <div class="container">
        <div class="col-12">
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <h2 class="pl-3 input-group w-100" style="color:#ffffff;">Validador de diploma</h2>
            </div>

            <div class="pointer mb-4 px-0 pb-3">
                <div class="row">
                    <div class="col-12" style="margin-bottom: 30px;">
                        <p>Ingresa el código para ver la información del diploma</p>

                        <form method="POST" action="{{ route('validar.diploma') }}" class="form-group">
                            {{ csrf_field() }}
                            <label for="diploma_code"><b>Código:</b></label>
                            <input type="text" name="code" class="form-control {{ ($errors->any() ? 'is-invalid' : '') }}" id="diploma_code" placeholder="Ingresa aquí el código del diploma">
                            @if ($errors->any())
                                <div class="invalid-feedback">{{ $errors->first() }}</div>
                            @endif

                            <button type="submit" class="btn btn-primary btn-lg mt-3 right">Validar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection