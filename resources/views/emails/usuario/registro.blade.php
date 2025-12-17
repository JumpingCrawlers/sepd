@component('mail::message')
# Registro de usuario

Estimado/a Dr./Dra.{{ $datos_usuario['nombre'] }},

Bienvenido a la web de la Sociedad Española de Patología Digestiva. Estos son tus datos de conexión:

Usuario: {{ $datos_usuario['usuario'] }}<br>
Contraseña: {{ $datos_usuario['password'] }}

Puedes cambiarlos desde tu perfil una vez te hayas conectado.

@component('mail::button', ['url' => config('app.url')])
Accede a la web
@endcomponent

No dudes en ponerte en contacto con nosotros para cualquier duda o comentario.

Atentamente,<br>
Secreataría de la SEPD

@endcomponent
