@component('mail::message')
# Cambios en el perfil

Se ha cambiado datos en el perfil de un usuario:

{{ $usuario }}

Los datos actualizados son los siguientes:

<ul>
@foreach($datos_cambios as $cambio)
<li>{{ $cambio['dato'] . ': ' . $cambio['antes'] . ' => ' . $cambio['despues'] }}</li>
@endforeach
</ul>
El sistema.

@endcomponent
