{{-- <div class="contenido-titulo-seccion borde-institucional mt-3">
    <span class="bg-institucional">Experiencia profesional</span>
</div> --}}

<div class="row">
    <label class="col-sm-2 col-form-label">Cargo actual:</label>
    <label class="col-sm-9 col-form-label">
        @if (isset($usuario->centros->cargo)) {{ $usuario->centros->cargo  }} @endif
    </label>
    <label class="col-sm-2 col-form-label">Centro:</label>
    <label class="col-sm-9 col-form-label">
        @if (isset($usuario->centros->centro)) {{ $usuario->centros->centro }} @endif
    </label>
    <label class="col-sm-2 col-form-label">Dirección:</label>
    <label class="col-sm-9 col-form-label">
        @if (isset($usuario->centros->direccion))
            {{ $usuario->centros->direccion }}
        @endif
        
        @if (isset($usuario->centros->cp))
            {{ "- {$usuario->centros->cp}" }}
        @endif
        
        @if (isset($usuario->centros->localidad))
            {{ $usuario->centros->localidad }}
        @endif
        
        @if (isset($usuario->centros->provincia))
            ({{ $usuario->centros->provincia }})
        @endif
    </label>
</div>
