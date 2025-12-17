@extends('socios.master')

@section('contenido-detalle')

<div class="contenido-titulo-pagina">
    Solicitud de socio
</div>

{{-- TRES OPCIONES: antes del pago o sin pago | después de pago erróneo | después de pago correcto --}}

<div class="container">
    <div class="row">
        <div class="col-2">
            @if (isset($retry) && $retry)
            <img src="{{ Voyager::image(setting('iconos.ko')) }}" border="0" class="img-fluid" alt="Error en el pago">
            @else
            <img src="{{ Voyager::image(setting('iconos.ok')) }}" border="0" class="img-fluid" alt="Registro correcto">
            @endif
        </div>
        <div class="col-9 text-justify">
            @if (isset($retry) && $retry)

            <p>
                Se ha producido algún error durante el proceso de pago. La transacción ha fallado y no se ha registrado la solicitud
                como completa.
            </p>
            <p>
                Si ha sido un error involuntario puedes volver a realizar el pago de la primera cuota pulsando en el botón inferior. 
            </p>
            <p>
                Alternativamente, puedes ponerte en contacto con la SEPD a través de la <a href="{{ route('contacto') }}" target="_blank">página de contacto</a>
                o los datos que encontrarás al pie de página.
            </p>

            @elseif (isset($finalizado) && $finalizado)

            <p>
                El pago ha sido realizado y tu solicitud se ha registrado correctamente.
            </p>

            <p>
                Recuerda que dicha solicitud está supeditada a la comprobación de tus datos. Una vez hayamos recibido la documentación necesaria,
                procederemos a la inscripción como socio en la SEPD y a comunicarte tus nuevos datos de socio.
            </p>


            @else

            <p>
                Hemos recibido correctamente tu solicitud de ingreso en la Sociedad Española de Patología Digestiva (SEPD).
                Agradecemos tu interés en formar parte de nuestra Sociedad.
            </p>
            <p>
                En tu cuenta de correo encontrarás un correo electrónico con el identificador de tu solicitud.
                Dicha solicitud está supeditada a la comprobación de tus datos. Una vez hayamos recibido la documentación necesaria,
                procederemos a la inscripción como socio en la SEPD y a comunicarte tus nuevos datos de socio.
            </p>

            @endif

            @if ($tpv == 1)

            <p>
                Según has seleccionado durante el registro, para finalizar la solicitud es necesario que satisfagas 
                la primera cuota a través de la tarjeta de crédito.
            </p>

            <form method="POST" action="{{ route('tpv') }}" class="mb-4">
                {{ csrf_field() }}
                <input type="hidden" name="uid" value="{{ $uid }}">
                <input type="hidden" name="descripcion" value="Primera cuota de socio">
                <button type="submit" class="btn"{{ getHtmlEstiloBoton('', '') }}>Proceder al pago</button>
            </form>

            <p>
                Al pulsar el botón serás dirigido a la página del banco. Desde allí, una vez finalizado el pago, 
                pulsa <strong>Continuar</strong> para finalizar el proceso y volver a la web de SEPD.
            </p>

            @else

            <p>
                En breve recibirás una comunicación por nuestra parte.
            </p>

            <p>
                Atentamente,<br>
                El equipo de SEPD
            </p>

            @endif
        </div>
    </div>
</div>

@endsection