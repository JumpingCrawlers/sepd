@component('mail::message')
<span class="Apple-style-span" style="border-collapse: separate; color: rgb(0, 0, 0); font-family: Calibri; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing:normal; line-height: normal; orphans: 2; text-align:-webkit-auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; -webkit-text-decorations-in-effect: none; -webkit-text-size-adjust: auto; -webkit-text-stroke-width:0px; font-size: medium; ">
    <p class="MsoNormal" style="margin-top: 0cm; margin-right:0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size:11pt; font-family: Calibri, sans-serif; ">
        <h1>Nueva solicitud de datos de acceso</h1>
        <p>
            <b>Nombre completo</b>: {{ $data['nombre_completo'] }}<br />
            <b>NIF</b>: {{ $data['nif'] }}<br />
            <b>¿Es socio?</b> {{ (($data['es_socio'] == '1') ? 'Sí' : 'No') }}<br />
            <b>Email</b>: {{ $data['mail'] }}<br />
            <b>Teléfono</b>: {{ $data['telefono'] }}<br />
            @if ($data['observaciones'])
            <b>Observaciones</b>: {{ $data['observaciones'] }}
            @endif
        </p>
    </p><br />
    <p class="MsoNormal" style="margin-top: 0cm; margin-right:0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size:11pt; font-family: Calibri, sans-serif; ">
        <b>Plataforma de formaci&oacute;n SEPD</b>
    </p>
    
    <p class="MsoNormal" style="margin-top: 0cm; margin-right:0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size:11pt; font-family: Calibri, sans-serif; ">
    <span style="font-size: 8pt; color: rgb(64, 49, 82);">
        C/ Sancho D&aacute;vila, 6<br />
        28028 Madrid, Spain</span>
    </p><br />
    
    <p class="MsoNormal" style="margin-top: 0cm; margin-right:0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size:11pt; font-family: Calibri, sans-serif; ">
        <span style="font-size: 7.5pt; color: purple; font-family:Verdana, sans-serif; " lang="EN-US">
            <b>Tel</b>: +34 91 402 13 53<br />
            <b>Email</b>: <a moz-do-not-send="true" href="mailto:formacion@sepd.es" style="color:blue; text-decoration: underline; ">formacion@sepd.es</a><br />
            <a moz-do-not-send="true" href="http://www.sepd.es" style="color: blue; text-decoration: underline; ">www.sepd.es/formacion</a>
        </span>
    </p><br />
    
    <p class="MsoNormal" style="margin-top: 0cm; margin-right:0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size:11pt; font-family: Calibri, sans-serif; line-height: 7px; ">
        <span style="font-size: 7.5pt; line-height: 5px; color: green; font-family: Tahoma, sans-serif; ">
            <b>Piense en el medioambiente antes de imprimir documentos.</b>
        </span>
    </p><br />
  
    <p class="MsoNormal" style="margin-top: 0cm; margin-right:0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size:11pt; font-family: Calibri, sans-serif; ">
        <span style="font-size: 6pt; color: purple; font-family:Verdana, sans-serif; ">
            <i>Este mensaje se dirige exclusivamente a su destinatario y puede contener informaci&oacute;n CONFIDENCIAL cuya divulgaci&oacute;n est&aacute; prohibida por la ley. Si ha recibido este mensaje por error debe saber que su lectura, copia y uso est&aacute;n prohibidos. Le rogamos que nos lo comunique inmediatamente por esta misma v&iacute;a o por tel&eacute;fono y proceda a su destrucci&oacute;n. El correo electr&oacute;nico v&iacute;a Internet no permite asegurar la confidencialidad de los mensajes que se transmiten ni su integridad o correcta recepci&oacute;n. La SEPD no asume responsabilidad por estas circunstancias. Si el destinatario de este mensaje no consintiera la utilizaci&oacute;n del correo electr&oacute;nico v&iacute;a Internet y la grabaci&oacute;n de los mensajes, rogamos lo ponga en nuestro conocimiento de forma inmediata. Su direcci&oacute;n de correo electr&oacute;nico ser&aacute; utilizada por la SEPD para el env&iacute;o de las comunicaciones necesarias que garanticen el mantenimiento de nuestras relaciones actuales. Si alguno de sus datos es incorrecto o si no desea recibir estas comunicaciones le rogamos que nos lo comunique dirigiendo su solicitud a su contacto habitual.</i>
        </span>
    </p>
</span>
@endcomponent