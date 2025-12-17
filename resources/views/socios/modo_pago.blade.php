<div class="contenido-titulo-seccion borde-institucional mb-4">
    <span class="bg-institucional">Modo de pago</span>
</div>

@if ($tipo_socio == "numerario" || $tipo_socio == "graduado_enfermeria")
<div class="form-group form-check">
    <input class="form-check-input" type="radio" name="modo_pago" id="modo_pago_domiciliacion" value="domiciliacion" required="required" {{ old('modo_pago')=='domiciliacion' ? 'checked' : '' }}>
    <label for="modo_pago_domiciliacion" class="form-check-label">
        Domiciliación bancaria
    </label>
</div>

<div class="collapse" id="containerDomiciliacion">

    <div class="form-group row">
        <label for="titular_cuenta" class="col-sm-2 col-form-label">Titular *</label>
        <div class="col-sm-10">
            <input name="titular_cuenta" type="text" value="{{ old('titular_cuenta') }}" class="form-control{{ $errors->has('titular_cuenta') ? ' is-invalid' : '' }}" placeholder="Titular">
            @if ($errors->has('titular_cuenta'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('titular_cuenta') }}</strong>
            </div>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="banco" class="col-sm-2 col-form-label">Banco o Caja *</label>
        <div class="col-sm-10">
            <input name="banco" type="text" value="{{ old('banco') }}" class="form-control{{ $errors->has('banco') ? ' is-invalid' : '' }}" placeholder="Banco o Caja">
            @if ($errors->has('banco'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('banco') }}</strong>
            </div>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="iban" class="col-sm-2 col-form-label">IBAN *</label>
        <div class="col-sm-10">
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <input name="iban" type="text" value="{{ old('iban') }}" class="form-control{{ $errors->has('iban') ? ' is-invalid' : '' }}" maxlength="4" size="2" placeholder="ES12">
                </div>
                <div class="col-auto">
                    <input name="iban_entidad" type="text" value="{{ old('iban_entidad') }}" class="form-control{{ $errors->has('iban_entidad') ? ' is-invalid' : '' }}" maxlength="4" size="2" placeholder="3456">
                </div>
                <div class="col-auto">
                    <input name="iban_oficina" type="text" value="{{ old('iban_oficina') }}" class="form-control{{ $errors->has('iban_oficina') ? ' is-invalid' : '' }}" maxlength="4" size="2" placeholder="7890">
                </div>
                <div class="col-auto">
                    <input name="iban_dc" type="text" value="{{ old('iban_dc') }}" class="form-control{{ $errors->has('iban_dc') ? ' is-invalid' : '' }}" maxlength="4" size="2" placeholder="1234">
                </div>
                <div class="col-auto">
                    <input name="iban_cuenta" type="text" value="{{ old('iban_cuenta') }}" class="form-control{{ $errors->has('iban_cuenta') ? ' is-invalid' : '' }}" maxlength="4" size="2" placeholder="5678">
                </div>
                <div class="col-auto">
                    <input name="iban_cuenta2" type="text" value="{{ old('iban_cuenta2') }}" class="form-control{{ $errors->has('iban_cuenta2') ? ' is-invalid' : '' }}" maxlength="4" size="2" placeholder="9012">
                </div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="iban_extranjero" id="iban_extranjero" value="otroIBAN">
                <label class="form-check-label" for="iban_extranjero">IBAN extranjero</label>
            </div>
        </div>
    </div>

</div>
@endif

<div class="form-group form-check">
    <input class="form-check-input" type="radio" name="modo_pago" id="modo_pago_tarjeta" value="tarjeta" required="required" {{ (old('modo_pago') == 'tarjeta' || $tipo_socio == "internacional") ? 'checked' : '' }}>
    <label for="modo_pago_tarjeta" class="form-check-label">
        Pago por tarjeta de crédito
    </label>
</div>

<div class="collapse multi-collapse" id="containerTarjeta">

    <p>
        Los datos proporcionados se utilizarán para las siguientes anualidades.<br>
        Para el pago de la cuota actual deberá hacerlo en la siguiente pantalla.
    </p>
    <p>
        Autorizo el cargo en mi tarjeta de crédito de las cuotas anuales de la SEPD:
    </p>
    <div class="form-group row">
        <label for="titular_tarjeta" class="col-sm-2 col-form-label">Titular *</label>
        <div class="col-sm-10">
            <input name="titular_tarjeta" type="text" value="{{ old('titular_tarjeta') }}" class="form-control{{ $errors->has('titular_tarjeta') ? ' is-invalid' : '' }}" placeholder="Titular">
            @if ($errors->has('titular_tarjeta'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('titular_tarjeta') }}</strong>
            </div>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Tipo de tarjeta *</label>
        <div class="col-sm-10">
            <div class="form-check">
                <input class="form-check-input{{ $errors->has('tipo_tarjeta') ? ' is-invalid' : '' }}" type="radio" name="tipo_tarjeta" id="tipo_tarjeta_visa" value="VISA" {{ old('tipo_tarjeta')=='VISA' ? 'checked' : '' }}>
                <label class="form-check-label" for="tipo_tarjeta_visa">VISA</label>
            </div>
            <div class="form-check">
                <input class="form-check-input{{ $errors->has('tipo_tarjeta') ? ' is-invalid' : '' }}" type="radio" name="tipo_tarjeta" id="tipo_tarjeta_mastercard" value="MasterCard" {{ old('tipo_tarjeta')=='MasterCard' ? 'checked' : '' }}>
                <label class="form-check-label" for="tipo_tarjeta_mastercard">MasterCard</label>
            </div>
            <div class="form-check">
                <input class="form-check-input{{ $errors->has('tipo_tarjeta') ? ' is-invalid' : '' }}" type="radio" name="tipo_tarjeta" id="tipo_tarjeta_amex" value="American Express" {{ old('tipo_tarjeta')=='American Express' ? 'checked' : '' }}>
                <label class="form-check-label" for="tipo_tarjeta_amex">American Express</label>
                @if ($errors->has('tipo_tarjeta'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('tipo_tarjeta') }}</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="numero_tarjeta" class="col-sm-2 col-form-label">Número de tarjeta *</label>
        <div class="col-sm-10">
            <div class="form-row align-items-center" id="containerVisa">
                <div class="col-auto">
                    <input name="numero_tarjeta_1" type="text" value="{{ old('numero_tarjeta_1') }}" class="form-control{{ $errors->has('numero_tarjeta_1') ? ' is-invalid' : '' }}" maxlength="4" size="2" placeholder="1234">
                </div>
                <div class="col-auto">
                    <input name="numero_tarjeta_2" type="text" value="{{ old('numero_tarjeta_2') }}" class="form-control{{ $errors->has('numero_tarjeta_2') ? ' is-invalid' : '' }}" maxlength="4" size="2" placeholder="5678">
                </div>
                <div class="col-auto">
                    <input name="numero_tarjeta_3" type="text" value="{{ old('numero_tarjeta_3') }}" class="form-control{{ $errors->has('numero_tarjeta_3') ? ' is-invalid' : '' }}" maxlength="4" size="2" placeholder="9012">
                </div>
                <div class="col-auto">
                    <input name="numero_tarjeta_4" type="text" value="{{ old('numero_tarjeta_4') }}" class="form-control{{ $errors->has('numero_tarjeta_4') ? ' is-invalid' : '' }}" maxlength="4" size="2" placeholder="3456">
                </div>
            </div>
            @if ($errors->has('numero_tarjeta_1') || $errors->has('numero_tarjeta_2') || $errors->has('numero_tarjeta_3') || $errors->has('numero_tarjeta_4'))
            <div id="errorNumVisa" class="invalid-feedback d-block">
                <strong>{{ $errors->first('numero_tarjeta_1') ?: $errors->first('numero_tarjeta_2') ?: $errors->first('numero_tarjeta_3') ?: $errors->first('numero_tarjeta_4')}}</strong>
            </div>
            @endif
            <div class="form-row align-items-center" id="containerAmex">
                <div class="col-auto">
                    <input name="numero_tarjeta_amex" type="text" value="{{ old('numero_tarjeta_amex') }}" class="form-control w-auto d-inline{{ $errors->has('numero_tarjeta_amex') ? ' is-invalid' : '' }}" maxlength="15" placeholder="123456789012345"> (sin espacios)
                    @if ($errors->has('numero_tarjeta_amex'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('numero_tarjeta_amex') }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="caducidad" class="col-sm-2 col-form-label">Caducidad *</label>
        <div class="col-sm-10">
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <select class="form-control{{ $errors->has('cad_mes') ? ' is-invalid' : '' }}" name="cad_mes">
                        <option value="1"@if(old('cad_mes')=="1") selected="selected"@endif>1</option>
                        <option value="2"@if(old('cad_mes')=="2") selected="selected"@endif>2</option>
                        <option value="3"@if(old('cad_mes')=="3") selected="selected"@endif>3</option>
                        <option value="4"@if(old('cad_mes')=="4") selected="selected"@endif>4</option>
                        <option value="5"@if(old('cad_mes')=="5") selected="selected"@endif>5</option>
                        <option value="6"@if(old('cad_mes')=="6") selected="selected"@endif>6</option>
                        <option value="7"@if(old('cad_mes')=="7") selected="selected"@endif>7</option>
                        <option value="8"@if(old('cad_mes')=="8") selected="selected"@endif>8</option>
                        <option value="9"@if(old('cad_mes')=="9") selected="selected"@endif>9</option>
                        <option value="10"@if(old('cad_mes')=="10") selected="selected"@endif>10</option>
                        <option value="11"@if(old('cad_mes')=="11") selected="selected"@endif>11</option>
                        <option value="12"@if(old('cad_mes')=="12") selected="selected"@endif>12</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select class="form-control{{ $errors->has('cad_anno') ? ' is-invalid' : '' }}" name="cad_anno">
                        @php
                        $anno = date("Y");
                        for($i = 0; $i < 7; $i++) {
                            $anno_mostrar = $anno+$i;
                            $selected = (old("cad_mes")==$anno_mostrar) ? ' selected="selected"' : '';
                            echo '<option value="'.$anno_mostrar.'"'.$selected.'>'.$anno_mostrar.'</option>';
                        }
                        @endphp
                    </select>
                </div>
            </div>
            @if ($errors->has('cad_anno') || $errors->has('cad_mes'))
            <div class="invalid-feedback d-block">
                <strong>{{ $errors->first('cad_anno') ?: $errors->first('cad_mes')}}</strong>
            </div>
            @endif
        </div>
    </div>

</div>
<hr class="borde-institucional">