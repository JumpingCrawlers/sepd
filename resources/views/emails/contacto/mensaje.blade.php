@component('mail::message')
# Contacto online

Estimado/a Dr./Dra.{{ $datos_contacto['nombre'] }},

Hemos recibido tu mensaje a través del formulario de contacto de nuestra web. Aquí tienes una copia del mismo como confirmación:

De: {{ $datos_contacto['nombre'] }}<br>
Asunto: {{ $datos_contacto['asunto'] }}<br>
Mensaje: {{ $datos_contacto['mensaje'] }}

Tu mensaje será atendido con la mayor brevedad posible.

Atentamente,<br>
Secretaría de la SEPD

@endcomponent
