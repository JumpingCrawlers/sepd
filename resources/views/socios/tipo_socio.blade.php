@extends('socios.master')

@section('contenido-detalle')

@if ($es_socio == 'vigente')
<div class="contenido-titulo-seccion borde-institucional">
    <span class="bg-institucional">Hazte socio</span>
</div>
<p>Usted ya es socio de la SEPD.</p>

@elseif($es_socio == 'baja')

<div class="contenido-titulo-seccion borde-institucional">
    <span class="bg-institucional">Hazte socio</span>
</div>
<p>Usted ya ha sido socio de la SEPD. Si quiere volver a darse de alta contacte con nosotros por telefono o email.</p>
<div>
    <p><strong style="color: #040b54;">Tel&eacute;fono</strong><span style="text-align: left;">: 91 402 13 53</span></p>
    <p><strong style="color: #040b54;">Horario</strong><span style="text-align: left;">: Lunes a jueves de 09:00h a 18:00h. Viernes de 09:00h a 15:00h.</span></p>
    <p><strong style="color: #040b54;">Horario de verano</strong><span style="text-align: left;">: </span> Lunes a jueves de 09:00h a 16:00h. Viernes de 09:00h a 15:00h.</p>
    <p><strong style="color: #040b54;">Email</strong><span style="text-align: left;">: sepd@sepd.es</span></p>
</div>
@else

<form action="/hazte_socio" method="POST" name="registro">
    {{ csrf_field() }}
    <div class="contenido-titulo-seccion borde-institucional">
        <span class="bg-institucional">Hazte socio</span>   
    </div>

    <p>Entra a formar parte de la más amplia asociación científica española dedicada a la patología del Aparato Digestivo y aprovecha sus ventajas.</p>
    <p>
        <a href="/ventajas_socios">Quiero conocer las ventajas de ser socio de la SEPD</a>
    </p>
    <p>Solicito ser admitido como miembro de la SEPD en calidad de:</p>

    @if ($errors->has('tipo_socio'))
    <div class="alert alert-danger">
        <strong>{!! $errors->first('tipo_socio') !!} Selecciona una de estas opciones.</strong>
    </div>
    @endif

    <div class="contenido-titulo-pagina color-institucional">
        Socio numerario
    </div>

    <div class="form-group form-check">
        <input class="form-check-input" type="radio" name="tipo_socio" id="tipo_socio_1" value="numerario" required="required" {{ old('remember') ? 'checked' : '' }}>
        <label for="tipo_socio_1" class="form-check-label">
            Numerario: Médicos especialistas en Aparato Digestivo, cirujanos, médicos de otras especialidades, médicos relacionados con la especialidad de Aparato Digestivo y titulados superiores.
        </label>
        <div class="callout institucional mt-2">
            <ul style="margin-bottom: 0;">
                <li>Enviar <u>Título Superior</u> a <a href="mailto:sepd@sepd.es">sepd@sepd.es</a>.</li>
                <li>La cuota anual de la SEPD es de <strong>{{ preg_replace("/\./",",",$cuota_numerario) }}&euro;</strong></li>
                <li>La aceptación como socio tendrá carácter provisional hasta su aprobación por la Junta Directiva.</li>
            </ul>
        </div>
    </div>

    <div class="contenido-titulo-pagina color-institucional">
        Socio en formación
    </div>

    <div class="form-group form-check">
        <input class="form-check-input" type="radio" name="tipo_socio" id="tipo_socio_2" value="formacion" required="required" {{ old('remember') ? 'checked' : '' }}>
        <label for="tipo_socio_2" class="form-check-label">
            Formación: Médicos residentes. Enviar certificación de residente a <a href="mailto:sepd@sepd.es">sepd@sepd.es</a>.
        </label>
        <div class="callout institucional mt-2">
            <ul style="margin-bottom: 0;">
                <li>Los socios en formación en España están exentos del pago de cuota previa acreditación de su condición aportando los datos de su tutor.</li>
                <li>La aceptación como socio tendrá carácter provisional hasta su aprobación por la Junta Directiva.</li>
            </ul>
        </div>
    </div>

    <div class="contenido-titulo-pagina color-institucional">
        Socio internacional
    </div>

    <div class="form-group form-check">
        <input class="form-check-input" type="radio" name="tipo_socio" id="tipo_socio_3" value="internacional" required="required" {{ old('remember') ? 'checked' : '' }}>
        <label for="tipo_socio_3" class="form-check-label">
            Internacional: Médicos residentes y especialistas en Aparato Digestivo de cualquier nacionalidad que no tengan ejercicio legal de la profesión en España.
        </label>
        <div class="callout institucional mt-2">
            <ul style="margin-bottom: 0;">
                <li>La cuota anual para socios internacionales es de <strong>{{ preg_replace("/\./",",",$cuota_internacional) }}&euro;</strong>, salvo aquellos cuya Sociedad Nacional haya suscrito acuerdo con la SEPD en los que estará sujeta a los términos del acuerdo.</li>
                <li>Si es el caso, enviar certificado acreditando su pertenencia a la Sociedad Nacional con acuerdo con la SEPD a <a href="mailto:sepd@sepd.es">sepd@sepd.es</a>.</li>
                <li>La aceptación como socio tendrá carácter provisional hasta su aprobación por la Junta Directiva.</li>
            </ul>
        </div>
    </div>

    <div class="contenido-titulo-pagina color-institucional">
        Socio graduado en enfermería
    </div>

    <div class="form-group form-check">
        <input class="form-check-input" type="radio" name="tipo_socio" id="tipo_socio_4" value="graduado_enfermeria" required="required" {{ old('remember') ? 'checked' : '' }}>
        <label for="tipo_socio_4" class="form-check-label">
            Graduados/as en enfermería que estén interesados/as en el conocimiento y el progreso de la Patología Digestiva y/o que presten sus servicios en el ámbito de la patología digestiva.
        </label>
        <div class="callout institucional mt-2">
            <ul style="margin-bottom: 0;">
                <li>Enviar Título Superior a <a href="mailto:sepd@sepd.es">sepd@sepd.es</a>.</li>
                <li>La cuota anual de la SEPD es de <strong>{{ preg_replace("/\./",",",$cuota_graduado_enfermeria) }}&euro;</strong>.</li>
                <li>Para su admisión precisan del aval del/la médico/a responsable de la unidad de digestivo o de alguna sección de digestivo, siempre que los/as mismos/as puedan acreditar su condición de socio/a de la SEPD.</li>
            </ul>
        </div>
    </div>

    <div class="form-group row float-right pr-4">
        <button type="submit" class="btn" {{ getHtmlEstiloBoton('', '') }}>Continuar</button>
    </div>

</form>
@endif

@endsection