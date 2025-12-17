@component('mail::message')
<p class="MsoNormal" style="margin-top: 0cm; margin-right:0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size:11pt; font-family: Calibri, sans-serif; ">
    El usuario {{ $sender->nombre_completo }} ({{ $sender->email }}) ha realizado la siguiente consulta al tutor/a {{ $receiver->nombre_completo }} para el curso <b style="font-size:11pt;">{{ $title }}<b>.<br /><br />
    <p>{{ $message }}</p>
</p><br />
<p class="MsoNormal" style="margin-top: 0cm; margin-right:0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size:11pt; font-family: Calibri, sans-serif; ">
    <b>Plataforma de formaci&oacute;n SEPD</b>
</p>

<p class="MsoNormal" style="margin-top: 0cm; margin-right:0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size:11pt; font-family: Calibri, sans-serif; ">
<span style="font-size: 8pt">
    C/ Sancho D&aacute;vila, 6<br />
    28028 Madrid, Spain</span>
</p><br />

<p class="MsoNormal" style="margin-top: 0cm; margin-right:0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size:11pt; font-family: Calibri, sans-serif; ">
    <span style="font-size: 7.5pt; font-family:Verdana, sans-serif; " lang="EN-US">
        <b>Tel</b>: +34 91 402 13 53<br />
        <b>Email</b>: <a moz-do-not-send="true" href="mailto:formaciononline@sepd.es" style="color:blue; text-decoration: underline; ">formaciononline@sepd.es</a><br />
        <a moz-do-not-send="true" href="http://www.sepd.es" style="color: blue; text-decoration: underline; ">www.sepd.es/formacion</a>
    </span>
</p><br />

<p class="MsoNormal" style="margin-top: 0cm; margin-right:0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size:11pt; font-family: Calibri, sans-serif; line-height: 7px; ">
    <span style="font-size: 7.5pt; line-height: 5px; color: green; font-family: Tahoma, sans-serif; ">
        <b>Piense en el medioambiente antes de imprimir documentos.</b>
    </span>
</p><br />

<p class="MsoNormal" style="box-sizing: border-box; line-height: 1.5em; margin-top: 0; text-align: left; margin-right: 0cm; margin-left: 0cm; margin-bottom: 0.0001pt; font-size: 8pt; margin-top: 10px; border-radius: 6px; ">
<i style="line-height: 1;">Este mensaje se dirige exclusivamente a su destinatario y puede contener información CONFIDENCIAL cuya divulgación está prohibida por la ley. Si ha recibido este mensaje por error debe saber que su lectura, copia y uso están prohibidos. Le rogamos que nos lo comunique inmediatamente por esta misma vía o por teléfono y proceda a su destrucción. El correo electrónico vía Internet no permite asegurar la confidencialidad de los mensajes que se transmiten ni su integridad o correcta recepción. La SEPD no asume responsabilidad por estas circunstancias. Si el destinatario de este mensaje no consintiera la utilización del correo electrónico vía Internet y la grabación de los mensajes, rogamos lo ponga en nuestro conocimiento de forma inmediata. Su dirección de correo electrónico será utilizada por la SEPD para el envío de las comunicaciones necesarias que garanticen el mantenimiento de nuestras relaciones actuales. Si alguno de sus datos es incorrecto o si no desea recibir estas comunicaciones le rogamos que nos lo comunique dirigiendo su solicitud a su contacto habitual.</i>
</p>
@endcomponent