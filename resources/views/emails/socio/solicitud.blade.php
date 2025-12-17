@component('mail::message')
# Solicitud de socio online

Estimado/a Dr./Dra.{{ $datos_solicitud['nombre'] }},

Muchas gracias por querer formar parte de la SEPD. Hemos recibido tu solicitud de socio {{ $datos_solicitud['tipo'] }} que tramitaremos a la mayor brevedad posible. Éste es el identificador de tu solicitud:

Identificador: {{ $datos_solicitud['uid'] }}

Contactaremos contigo cuando sea efectiva. No dudes en ponerte en contacto con nosotros para cualquier duda o comentario.

Atentamente,<br>
Secretaría de la SEPD

@endcomponent
