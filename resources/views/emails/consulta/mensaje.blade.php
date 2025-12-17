@component('mail::message')
# Consulta online

Estimado/a Dr./Dra.{{ $datos_consulta['nombre'] }},

Hemos recibido su consulta a través del formulario de consultas de nuestra web. Aquí tiene una copia de la misma como confirmación:

De: {{ $datos_consulta['nombre'] }}<br>
Centro: {{ $datos_consulta['centro'] }}<br>
Área de gestión: {{ $datos_consulta['descripcion_area_gestion'] }}<br>
Consulta: {{ $datos_consulta['consulta'] }}

Su consulta será atendida con la mayor brevedad posible.

Atentamente,<br>
Secretaría de la SEPD

@endcomponent
