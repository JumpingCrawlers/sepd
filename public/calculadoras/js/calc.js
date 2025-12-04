var xmlDoc;

if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp = new XMLHttpRequest();
}
else {// code for IE6, IE5
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}

xmlhttp.open("GET", "datos.xml", true);
xmlhttp.send();
xmlDoc = xmlhttp.responseXML;

function onlyNumbers(input, evt) {
    evt = (evt) ? evt : window.event;
    
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    
    //comma=44, period=46,backspace=8,tab=9,enter=13,shift=16,ctrl=17,alt=18,pause/break=19,caps lock=20,escape=27,page up=33,page down=34,end=35,home=36,left arrow=37,up arrow=38,right arrow=39,down arrow=40,insert=45,delete=46
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 44 && charCode != 46 && charCode != 8 && charCode != 9 && charCode != 13 && charCode != 16 && charCode != 17 && charCode != 18 && charCode != 19 && charCode != 20 && charCode != 27 && charCode != 33 && charCode != 34 && charCode != 35 && charCode != 36 && charCode != 37 && charCode != 38 && charCode != 39 && charCode != 40 && charCode != 45 && charCode != 46)
        return false;

    if ((charCode == 44 || charCode == 46) && input.value.indexOf(',') > 0)
        return false;
    else if (charCode == 46) {
        input.value += ',';
        return false;
    }

    return true;

}

function populateFormulas() {

    document.getElementById("innerContainer").style.visibility = "hidden";
    document.getElementById("referencia").style.visibility = "hidden";

    var formulas;
    formulas = "<ul>";
    
    formulas += "<li class=\"li_depth1\"><a href=\"#\">Gastrointestinal</a><ul>";
    $(xmlDoc).find('formula').each(function() {
        var tipo = $(this).attr('tipo');
        if (tipo == 'gastro') {
            formulas += "<li><a href=\"javascript:selectFormula('" + $(this).attr('id') + "');\">" + $(this).find('titulo').text() + "</a></li>";
        }

    });
    formulas += "</ul></li>";
    formulas += "<li class=\"li_depth1\"><a href=\"#\">Hepatolog&iacute;a</a><ul>";
    $(xmlDoc).find('formula').each(function() {
        var tipo = $(this).attr('tipo');
        if (tipo == 'hepato') {
            formulas += "<li><a href=\"javascript:selectFormula('" + $(this).attr('id') + "');\">" + $(this).find('titulo').text() + "</a></li>";
        }

    });
    formulas += "</ul></li>";
    
    formulas += "<li class=\"li_depth1\"><a href=\"#\">Generales</a><ul>";
    $(xmlDoc).find('formula').each(function() {
        var tipo = $(this).attr('tipo');
        if (tipo == 'general') {
            formulas += "<li><a href=\"javascript:selectFormula('" + $(this).attr('id')  + "');\">" + $(this).find('titulo').text() + "</a></li>";
        }

    });
    formulas += "</ul></li>";
    
    formulas += "<li class=\"li_depth1\"><a href=\"#\">Epidemiolog&iacute;a Cl&iacute;nica</a><ul>";
    $(xmlDoc).find('formula').each(function() {
        var tipo = $(this).attr('tipo');
        if (tipo == 'epide') {
            formulas += "<li><a href=\"javascript:selectFormula('" + $(this).attr('id') + "');\">" + $(this).find('titulo').text() + "</a></li>";
        }

    });
    formulas += "</ul></li>";
    formulas += "</ul>";

    document.getElementById("dhtmlgoodies_slidedown_menu").innerHTML = formulas;

    document.getElementById("inputvariables").innerHTML = "";
    document.getElementById("resultado").innerHTML = "";
    document.getElementById("referencia").innerHTML = "";
    document.getElementById("titulo").innerHTML = "";
    
    selectFormula("gas1");
    
}

function selectFormula(formula) {

    var stemp = '';
    document.getElementById("inputvariables").innerHTML = "";
    document.getElementById("resultado").innerHTML = "";
    document.getElementById("referencia").innerHTML = "";
    document.getElementById("titulo").innerHTML = "";
    document.getElementById("innerContainer").style.visibility = "visible";
    document.getElementById("referencia").style.visibility = "visible";
    document.getElementById("referencia").style.marginTop = "";
    document.getElementById("epi3_inputvariables").style.visibility = "hidden";
    document.getElementById("epi3_resultado").style.visibility = "hidden";
    document.getElementById("epi3_inputvariables").style.height = "0px";
    document.getElementById("epi3_resultado").style.height = "0px";
    
    if (formula != "") {

        if (formula=='epi3')        
             document.getElementById("referencia").style.marginTop = "110px";
        else if (formula=='gas3')        
             document.getElementById("referencia").style.marginTop = "30px";
        else if (formula=='gas6')        
             document.getElementById("referencia").style.marginTop = "390px";
        else if (formula=='gas8')        
             document.getElementById("referencia").style.marginTop = "5px";
        else if (formula=='gas9')        
             document.getElementById("referencia").style.marginTop = "870px";
            
        document.getElementById("formula").value = formula;

        $(xmlDoc).find('formula').each(function() {
            var id = $(this).attr('id');
            if (id == formula) {
                $(this).find('inputvariables').find('variable').each(function() {
                    stemp = '<br/><div class=\'bg_purple\'><div class=\'bl_purple\'><div class=\'br_purple\'><div class=\'tl_purple\'><div class=\'tr_purple\'><span class=\'titulo2\'>' + $(this).attr('nombre') + '</span><input type=\'text\' id=\'v' + $(this).attr('id') + '\' name=\'v' + $(this).attr('id') + '\' onkeypress=\'javascript: return onlyNumbers(this,event);\'/> ';
                    if ($(this).attr('unidad'))
                        stemp += '<span class=\'titulo2_unidad\'>' + $(this).attr('unidad') + '</span>';

                    stemp += '</div></div></div></div></div><div class=\'clear_purple\'>&nbsp;</div>';
                    document.getElementById("inputvariables").innerHTML += stemp;
                });
                $(this).find('condicionales').find('condicion').each(function() {
					
					if (formula=='gas6'){
										
							if ($(this).attr('id')=='6'){
									document.getElementById("inputvariables").innerHTML += '<br/><div class="subtitulo">Criterios ductales</div><span class=\'titulo3\'>' + $(this).attr('nombre') + '</span>&nbsp;';
							}else{
								document.getElementById("inputvariables").innerHTML += '<br/><span class=\'titulo3\'>' + $(this).attr('nombre') + '</span>&nbsp;';
							}
					}else if(formula=='gas3'){
						//alert($(this).attr('id'));
                            $('#subtitulo').css('display', 'none');
                         
							if ($(this).attr('id')=='1'){
									document.getElementById("inputvariables").innerHTML += '<br/><div class="subtitulo">Al ingreso</div><span id="gas3_span_'+$(this).attr('id')+'" class=\'titulo3\'>' + $(this).attr('nombre') + '</span>&nbsp;';
							}else if ($(this).attr('id')=='6'){
                                    document.getElementById("inputvariables").innerHTML += '<br/><div class="subtitulo">A las 48h</div><span id="gas3_span_'+$(this).attr('id')+'" class=\'titulo3\'>' + $(this).attr('nombre') + '</span>&nbsp;';
                            }else{
								document.getElementById("inputvariables").innerHTML += '<br/><span id="gas3_span_'+$(this).attr('id')+'" class=\'titulo3\'>' + $(this).attr('nombre') + '</span>&nbsp;';
                            
							}

					}else{
							document.getElementById("inputvariables").innerHTML += '<br/><span  class=\'titulo3\'>' + $(this).attr('nombre') + '</span>&nbsp;';
					}
                    
                    var id = $(this).attr('id');
					var radio_buttons = '';
					if ($(this).attr('tipo') == 'radio' && $(this).attr('nombre') != 'Sexo') {
						radio_buttons += "<div class='SiNo'>";
						$(this).find('opciones').find('opcion').each(function() {
							var checked ="";
							if ($(this).attr('valor')== '0') checked = 'checked="checked"';
							radio_buttons += '<input type=\'radio\' '+checked+'  id=\'c' + id + '\' name=\'c' + id + '\' value=\'' + $(this).attr('valor') + '\'/>&nbsp;<label class=\'titulo3_opcion\' onclick="javascript: radioSelected(\'c'+id+'\',\''+$(this).attr('valor')+'\',\''+formula+'\')">' + $(this).attr('nombre') + '</label>';
						});
						radio_buttons += '</div>';
						 document.getElementById("inputvariables").innerHTML += radio_buttons;
                    }else if ($(this).attr('tipo') == 'radio' && $(this).attr('nombre') == 'Sexo') {
						var radio_buttons = '';
						radio_buttons += "<div class='SiNo'>";
						$(this).find('opciones').find('opcion').each(function() {
                           // document.getElementById("inputvariables").innerHTML += '<input type=\'radio\' id=\'c' + id + '\' name=\'c' + id + '\' value=\'' + $(this).attr('valor') + '\' onclick=\'javascript:radioSelected(this.name,this,"' +formula+'");\'/>&nbsp;<span class=\'titulo3_opcion\'>' + $(this).attr('nombre') + '</span>';
							
								if ($(this).attr('valor') == "M") {
									radio_buttons += '<input type=\'radio\' id=\'c' + id + '\' name=\'c' + id + '\' value=\'' + $(this).attr('valor') + '\' checked="checked"/>&nbsp;<label class=\'titulo3_opcion\' onclick="javascript: radioSelected(\'c'+id+'\',\''+$(this).attr('valor')+'\',\''+formula+'\')"><i class=\'fa fa-female \'></i></label>';
								}else{
									radio_buttons += '<input type=\'radio\' id=\'c' + id + '\' name=\'c' + id + '\' value=\'' + $(this).attr('valor') + '\' />&nbsp;<label class=\'titulo3_opcion\' onclick="javascript: radioSelected(\'c'+id+'\',\''+$(this).attr('valor')+'\',\''+formula+'\')"><i class=\'fa fa-male \'></i></label>';
								}
						});
						radio_buttons += '</div>';
							document.getElementById("inputvariables").innerHTML += radio_buttons;
                    }
                    else if ($(this).attr('tipo') == 'select') {
                        var options = '';
                        options += '<select class=\'wide\' id=\'c' + id + '\' name=\'c' + id + '\'>';
                        $(this).find('opciones').find('opcion').each(function() {
                            options += '<option value=\'' + $(this).attr('valor') + '\'/>' + $(this).attr('nombre') + '</option>';
                        });
                        document.getElementById("inputvariables").innerHTML += options + '</select><br/>';
                    }
								
                });
                document.getElementById("inputvariables").innerHTML += '<br/><br/><div class=\'boton_borrar\'><a href=\'javascript:selectFormula("' + document.getElementById("formula").value + '");\'><img src=\'images/button_delete.png\' alt=\'Calcular\'/ border=\'0\'></a></div>';
                document.getElementById("inputvariables").innerHTML += '<div class=\'boton_calcular\'><a href=\'javascript:calcular("' + formula + '");\'><img src=\'images/button_calc.png\' alt=\'Calcular\'/ border=\'0\'></a></div><br/><br/><br/>';

                document.getElementById("titulo").innerHTML = $(this).find('titulo').text();
				if (id!='hep3'){
					if ($(this).find('descripcion').text() != '') {
						if ($(this).find('descripcion').attr('visible') == 'false')
							ocultarDescripcion(formula);
						else
							mostrarDescripcion(formula);
					}
				}else{
		
					document.getElementById("descripcion").innerHTML = "<a href=\"javascript:mostrarDescripcion('" + id + "');\">ver guía explicativa</a>";
				}
                if ($(this).find('referencia').text() != '')
                    document.getElementById("referencia").innerHTML = 'Referencia: ' + $(this).find('referencia').text();
            }
        });
		
			$('#subtitulo, .subtitulo').css('display', 'none');
		if (formula=='gas3') {

			    $('.subtitulo').css('display', 'block');
                $('#subtitulo').css('display', 'none');
			/*if ($(this).hasClass('separador')){
				 stemp +='<span class="subtitulo"> Al ingreso</span>';					
			} 
			if ($(this).hasClass('separador-2')){
				 stemp +='<span class="subtitulo"> A las 48 horas</span>';					
			} */
		}
		
			if (formula=='gas6') {
				$('#subtitulo, .subtitulo').css('display', 'block');
				
				jQuery('#innerContainer #subtitulo').addClass('subtitulo').html('Criterios parenquimatosos');
				if ($(this).hasClass('separador')){
				 stemp +='<span id="ductales" class="subtitulo"> Criterios ductales</span>';					
			} 
		}
        
		
        if (formula=='epi3'){
            document.getElementById("epi3_inputvariables").style.visibility = "visible";
            document.getElementById("epi3_inputvariables").style.height = "460px";
            document.getElementById('var1').value = '';
            document.getElementById('var2').value = '';
            document.getElementById('var3').value = '';
            document.getElementById('var4').value = '';
            document.getElementById('var5').value = '';
        }
    }


    $(document).ready(function() {
        if ($.browser.msie) $('select.wide')
                    .bind('focus mouseover', function() { $(this).addClass('expand').removeClass('clicked'); })
                    .bind('click', function() { $(this).toggleClass('clicked'); })
                    .bind('mouseout', function() { if (!$(this).hasClass('clicked')) { $(this).removeClass('expand'); } })
                    .bind('blur', function() { $(this).removeClass('expand clicked'); });
    });
	
}

function mostrarDescripcion(id) {

    var formula = $(xmlDoc).find('formula[id=\'' + id + '\']');

   

	document.getElementById("descripcion").innerHTML = $(formula).find('descripcion').text();
	document.getElementById("descripcion").innerHTML += "<br/><br/><a href=\"javascript:ocultarDescripcion('" + id + "');\">ocultar guía explicativa</a>";
	
    
}

function ocultarDescripcion(id) {

    document.getElementById("descripcion").innerHTML = "<a href=\"javascript:mostrarDescripcion('" + id + "');\">ver guía explicativa</a>";
}

function validateInput(id) {
    var valido = true;

    var formula = $(xmlDoc).find('formula[id=\'' + id + '\']');

    $(formula).find('inputvariables').find('variable').each(function() {
		if (id=="hep3" && $(this).attr('id')!=4){
			if (eval('document.getElementById(\'v' + $(this).attr('id') + '\').value') == '')
            valido = false;
		}
    });

    $(formula).find('condicionales').find('condicion').each(function() {
    if ($(this).attr('tipo') == 'radio') {
		if (id=="hep3" && $(this).attr('id')!=4){
            if (!getRadioValue('c' + $(this).attr('id')))
                valido = false;
			}
	}
    else if ($(this).attr('tipo') == 'select') {
			if (id=="hep2" &&  $(this).attr('id')!='6'){
				if(eval('document.calcform.c' + $(this).attr('id') + '.value') == '')
			    valido = false;
			}
        }
    });
    
    return valido;
}

function calcular(id) {
    if (id == 'gas6')
        return calcular_gas6();
	if (id=='hep3')
		hep3 = calcular_hep3();
    if (id == 'epi3')
        return calcular_epi3();
        
    if (!validateInput(id)) {
        alert('Debe completar todos los datos');
        return;
    }

    document.getElementById("resultado").innerHTML = '';

    var resultado;
    var acumulado;
    var acumulado2;
    var arrayVariables = [];
    var arrayOperaciones = [];
    var condicion;
    var formula = $(xmlDoc).find('formula[id=\'' + id + '\']');

    var unidadresultado = '';
    var mostrarresultadototal = true;
    $(formula).find('calculos').find('calculo').each(function() {
        condicion = true;
        if ($(this).find('condicionescalculo')) {
            $(this).find('condicionescalculo').find('condicion').each(function() {
                if ($(formula).find('condicionales').find('condicion[id=\'' + $(this).attr('id') + '\']').attr('tipo') == 'radio') {
                    if ($(this).attr('valor') != getRadioValue('c' + $(this).attr('id')))
                        condicion = false;
                }
                else if ($(formula).find('condicionales').find('condicion[id=\'' + $(this).attr('id') + '\']').attr('tipo') == 'select') {
                    if ($(this).attr('valor') != eval('document.calcform.c' + $(this).attr('id') + '.value'))
                        condicion = false;
                }
            });
        }

        if (condicion) {
            $(this).find('variables').find('variable').each(function() {
                if ($(this).attr('tipo') == 'cond') {
                    var valor = document.getElementById('v' + $(this).attr('id')).value.replace(',', '.');
                    condicion = true;
                    if ($(this).find('condiciones')) {
                        $(this).find('condiciones').find('condicion').each(function() {
                            if ($(formula).find('condicionales').find('condicion[id=\'' + $(this).attr('id') + '\']').attr('tipo') == 'radio') {
                                if ($(this).attr('valor') != getRadioValue('c' + $(this).attr('id')))
                                    condicion = false;
                            }
                            else if ($(formula).find('condicionales').find('condicion[id=\'' + $(this).attr('id') + '\']').attr('tipo') == 'select') {
                                if ($(this).attr('valor') != eval('document.calcform.c' + $(this).attr('id') + '.value'))
                                    condicion = false;
                            }
                        });
                    }

                    if (condicion) {
                        arrayVariables.length++;
                        $(this).find('rangos').find('rango').each(function() {
                            if ($(this).attr('ini') && $(this).attr('fin')) {
                                if (parseFloat(valor) >= parseFloat($(this).attr('ini')) && parseFloat(valor) < parseFloat($(this).attr('fin')))
                                    arrayVariables[arrayVariables.length - 1] = parseFloat($(this).attr('valor'));
                            }
                            else if ($(this).attr('ini')) {
                                if (parseFloat(valor) >= parseFloat($(this).attr('ini')))
                                    arrayVariables[arrayVariables.length - 1] = parseFloat($(this).attr('valor'));
                            }
                            else if ($(this).attr('fin')) {
                                if (parseFloat(valor) < parseFloat($(this).attr('fin')))
                                    arrayVariables[arrayVariables.length - 1] = parseFloat($(this).attr('valor'));
                            }
                        });
                    }
                }
                else if ($(this).attr('tipo') == 'opcion') {
                    arrayVariables.length++;
                    if (!eval('document.calcform.c' + $(this).attr('id') + '.value'))
                        arrayVariables[arrayVariables.length - 1] = getRadioValue('c' + $(this).attr('id'));
                    else
                        arrayVariables[arrayVariables.length - 1] = eval('document.calcform.c' + $(this).attr('id') + '.value');

                }
                else if ($(this).attr('id')) {
                    arrayVariables.length++;
                    arrayVariables[arrayVariables.length - 1] = document.getElementById('v' + $(this).attr('id')).value.replace(',', '.');
                    if ($(this).attr('pow'))
                        arrayVariables[arrayVariables.length - 1] = Math.pow(arrayVariables[arrayVariables.length - 1], $(this).attr('pow'));
                    if ($(this).attr('log'))
                        arrayVariables[arrayVariables.length - 1] = Math.log(arrayVariables[arrayVariables.length - 1]);
                    if ($(this).attr('multi'))
                        arrayVariables[arrayVariables.length - 1] = arrayVariables[arrayVariables.length - 1] * $(this).attr('multi');
                }
                else if ($(this).attr('constante')) {
                    arrayVariables.length++;
                    arrayVariables[arrayVariables.length - 1] = $(this).attr('constante');
                }
            });


            $(this).find('operaciones').find('operacion').each(function() {
                arrayOperaciones.length++;
                arrayOperaciones[arrayOperaciones.length - 1] = $(this).attr('tipo');
            });

            unidadresultado = $(this).find('unidad').text();

            if ($(this).attr('resultado')) {
                mostrarresultadototal = false;
                resultado = parseFloat(arrayVariables[0]);
                for (var i = 1; i < arrayVariables.length; i++) {
                    //alert(resultado + ' ' + arrayOperaciones[i - 1] + ' ' + arrayVariables[i]);
                    if (arrayOperaciones[i - 1] == '+')
                        resultado += parseFloat(arrayVariables[i]);
                    else if (arrayOperaciones[i - 1] == '-')
                        resultado -= parseFloat(arrayVariables[i]);
                    else if (arrayOperaciones[i - 1] == '*')
                        resultado = resultado * parseFloat(arrayVariables[i]);
                    else if (arrayOperaciones[i - 1] == '/')
                        resultado = resultado / parseFloat(arrayVariables[i]);
                    else if (arrayOperaciones[i - 1] == 'log')
                        resultado = Math.log(resultado);
                    else if (arrayOperaciones[i - 1] == 'pow')
                        resultado = Math.pow(resultado, parseFloat(arrayVariables[i]));
                    else if (arrayOperaciones[i - 1] == 'exp')
                        resultado = Math.exp(resultado);
                    else if (arrayOperaciones[i - 1] == 'roundup')
                        resultado = Math.ceil(resultado);
                    else if (arrayOperaciones[i - 1] == 'abs')
                        resultado = Math.abs(resultado);
                    else if (arrayOperaciones[i - 1] == 'sqrt')
                        resultado = Math.sqrt(resultado);
                    else if (arrayOperaciones[i - 1] == 'concat')
                        resultado = resultado + '' + arrayVariables[i];
                    else if (arrayOperaciones[i - 1] == 'acumular') {
                        acumulado = resultado;
                        resultado = parseFloat(arrayVariables[i]); 
                    }
                    else if (arrayOperaciones[i - 1] == '+acumulado')
                        resultado = resultado + acumulado;
                    else if (arrayOperaciones[i - 1] == '-acumulado')
                        resultado = resultado - acumulado;
                    else if (arrayOperaciones[i - 1] == '*acumulado')
                        resultado = resultado * acumulado;
                    else if (arrayOperaciones[i - 1] == '/acumulado')
                        resultado = resultado / acumulado;
                    else if (arrayOperaciones[i - 1] == 'sqrt_acumulado')
                        resultado = Math.sqrt(acumulado);

                    else if (arrayOperaciones[i - 1] == 'acumular2') {
                        acumulado2 = resultado;
                        resultado = parseFloat(arrayVariables[i]);
                    }
                    else if (arrayOperaciones[i - 1] == '+acumulado2')
                        resultado = resultado + acumulado2;
                    else if (arrayOperaciones[i - 1] == '-acumulado2')
                        resultado = resultado - acumulado2;
                    else if (arrayOperaciones[i - 1] == '*acumulado2')
                        resultado = resultado * acumulado2;
                    else if (arrayOperaciones[i - 1] == '/acumulado2')
                        resultado = resultado / acumulado2;
                    else if (arrayOperaciones[i - 1] == 'sqrt_acumulado2')
                        resultado = Math.sqrt(acumulado2);
                }

                var decimales = 2;
                if ($(this).attr('decimales'))
                    decimales = $(this).attr('decimales');

                if ($(this).attr('tipo') == 'resultado') {
                    document.getElementById("resultado").innerHTML += '<span class=\'titulo4\'>' + $(this).attr('resultado') + '&nbsp;</span><span class=\'titulo5\'>' + resultado.toFixed(decimales).replace('.', ',') + '</span>';

                    if (unidadresultado != '')
                        document.getElementById("resultado").innerHTML += '&nbsp;<span class=\'titulo4_unidad\'>' + unidadresultado + '</span>';

                    document.getElementById("resultado").innerHTML += '<br/>';
                }
                else {
                    document.getElementById("resultado").innerHTML += '<span class=\'titulo4\'>Equivale a&nbsp;</span><span class=\'titulo5\'>' + resultado.toFixed(decimales).replace('.', ',') + '</span>';

                    if (unidadresultado != '')
                        document.getElementById("resultado").innerHTML += '<span class=\'titulo4_unidad\'>&nbsp;' + unidadresultado + '</span>';

                    document.getElementById("resultado").innerHTML += '<span class=\'titulo4_unidad\'>&nbsp;de&nbsp;' + $(this).attr('resultado') + '</span><br/>';
                }

                arrayVariables = [];
                arrayOperaciones = [];
                unidadresultado = '';
            }
        }
    });

    if (mostrarresultadototal) {
        
        resultado = arrayVariables[0];
        
        for (var i = 1; i < arrayVariables.length; i++) {
            //alert(resultado + ' ' + arrayOperaciones[i - 1] + ' ' + arrayVariables[i]);
            if (arrayOperaciones[i - 1] == '+') {
                resultado = parseFloat(resultado) + parseFloat(arrayVariables[i]);
            }
            else if (arrayOperaciones[i - 1] == '-')
                resultado = parseFloat(resultado) - parseFloat(arrayVariables[i]);
            else if (arrayOperaciones[i - 1] == '*')
                resultado = parseFloat(resultado) * parseFloat(arrayVariables[i]);
            else if (arrayOperaciones[i - 1] == '/')
                resultado = parseFloat(resultado) / parseFloat(arrayVariables[i]);
            else if (arrayOperaciones[i - 1] == 'log')
                resultado = Math.log(parseFloat(resultado));
            else if (arrayOperaciones[i - 1] == 'pow')
                resultado = Math.pow(resultado, parseFloat(arrayVariables[i]));
            else if (arrayOperaciones[i - 1] == 'exp')
                resultado = Math.exp(parseFloat(resultado));
            else if (arrayOperaciones[i - 1] == 'roundup')
                resultado = Math.ceil(parseFloat(resultado));
            else if (arrayOperaciones[i - 1] == 'abs')
                resultado = Math.abs(parseFloat(resultado));
            else if (arrayOperaciones[i - 1] == 'sqrt')
                resultado = Math.sqrt(parseFloat(resultado));
            else if (arrayOperaciones[i - 1] == 'concat')
                resultado = resultado + '' + arrayVariables[i];
            else if (arrayOperaciones[i - 1] == 'acumular') {
                acumulado = resultado;
                resultado = parseFloat(arrayVariables[i]);
            }
            else if (arrayOperaciones[i - 1] == '+acumulado')
                resultado = parseFloat(resultado) + parseFloat(acumulado);
            else if (arrayOperaciones[i - 1] == '-acumulado')
                resultado = parseFloat(resultado) - parseFloat(acumulado);
            else if (arrayOperaciones[i - 1] == '*acumulado')
                resultado = parseFloat(resultado) * parseFloat(acumulado);
            else if (arrayOperaciones[i - 1] == '/acumulado')
                resultado = parseFloat(resultado) / parseFloat(acumulado);
            else if (arrayOperaciones[i - 1] == 'sqrt_acumulado')
                resultado = Math.sqrt(parseFloat(acumulado));
            else if (arrayOperaciones[i - 1] == 'acumular2') {
                acumulado2 = resultado;
                resultado = parseFloat(arrayVariables[i]);
            }
            else if (arrayOperaciones[i - 1] == '+acumulado2')
                resultado = parseFloat(resultado) + parseFloat(acumulado2);
            else if (arrayOperaciones[i - 1] == '-acumulado2')
                resultado = parseFloat(resultado) - parseFloat(acumulado2);
            else if (arrayOperaciones[i - 1] == '*acumulado2')
                resultado = parseFloat(resultado) * parseFloat(acumulado2);
            else if (arrayOperaciones[i - 1] == '/acumulado2')
                resultado = parseFloat(resultado) / parseFloat(acumulado2);
            else if (arrayOperaciones[i - 1] == 'sqrt_acumulado2')
                resultado = Math.sqrt(parseFloat(acumulado2));
        }
        
    if ($(formula).find('resultados'))
        if (!($(formula).find('resultados').attr('visible') && $(formula).find('resultados').attr('visible') == 'false')) {
            if ($(formula).find('resultados').attr('decimales')) {
                if ($(formula).find('resultados').attr('decimales') == 'texto')
                    document.getElementById("resultado").innerHTML = '<span class=\'titulo4\'>Resultado:&nbsp;</span><span class=\'titulo5\'>' + resultado + '</span>';
                else
                    document.getElementById("resultado").innerHTML = '<span class=\'titulo4\'>Resultado:&nbsp;</span><span class=\'titulo5\'>' + parseFloat(resultado).toFixed($(formula).find('resultados').attr('decimales')).replace('.', ',') + '</span>';
            }
            else
                document.getElementById("resultado").innerHTML = '<span class=\'titulo4\'>Resultado:&nbsp;</span><span class=\'titulo5\'>' + parseFloat(resultado).toFixed(2).replace('.', ',') + '</span>';            
        }
    if (unidadresultado!='')
        document.getElementById("resultado").innerHTML += '&nbsp;<span class=\'titulo4_unidad\'>' + unidadresultado + '</span>';

    }
    
    if ($(formula).find('resultados')) {
        var interpreta='';
        $(formula).find('resultados').find('resultado').each(function() {
            condicion = true;
            if ($(this).find('condiciones')) {
                $(this).find('condiciones').find('condicion').each(function() {
                    if ($(formula).find('condicionales').find('condicion[id=\'' + $(this).attr('id') + '\']').attr('tipo') == 'radio') {
                        if ($(this).attr('valor') != getRadioValue('c' + $(this).attr('id')))
                            condicion = false;
                    }
                });
            }

            if (condicion) {
                $(this).find('rangos').find('rango').each(function() {
                    if ($(this).attr('ini') && $(this).attr('fin')) {
                        if (resultado >= $(this).attr('ini') && resultado < $(this).attr('fin'))
                            interpreta = $(this).text();
                    }
                    else if ($(this).attr('ini')) {
                        if (resultado >= $(this).attr('ini'))
                            interpreta = $(this).text();
                    }
                    else if ($(this).attr('fin')) {
                        if (resultado < $(this).attr('fin'))
                            interpreta = $(this).text();
                    }
                });
            }
        });
        
        if (interpreta != '') {
            if ($(formula).find('resultados').attr('visible') && $(formula).find('resultados').attr('visible') == 'false')
                document.getElementById("resultado").innerHTML += '<span class=\'titulo4_unidad\'>' + interpreta + '</span>';
            else
                document.getElementById("resultado").innerHTML += '&nbsp;<span class=\'titulo4_unidad\'>(' + interpreta + ')</span>';
        }
    }

    arrayVariables = [];
    arrayOperaciones = [];
    //Si existe resultado ideal
    if ($(formula).find('ideales')) {
        $(formula).find('ideales').find('ideal').each(function() {
            condicion = true;
            if ($(this).find('condiciones')) {
                $(this).find('condiciones').find('condicion').each(function() {
                    if ($(formula).find('condicionales').find('condicion[id=\'' + $(this).attr('id') + '\']').attr('tipo') == 'radio') {
                        if ($(this).attr('valor') != getRadioValue('c' + $(this).attr('id')))
                            condicion = false;
                    }
                });
            }

            if (condicion) {
                $(this).find('calculoideal').find('variable').each(function() {
                    arrayVariables.length++;
                    if ($(this).attr('id'))
                        arrayVariables[arrayVariables.length - 1] = document.getElementById('v' + $(this).attr('id')).value.replace(',', '.');
                    else if ($(this).attr('constante'))
                        arrayVariables[arrayVariables.length - 1] = $(this).attr('constante');
                });


                $(this).find('calculoideal').find('operacion').each(function() {
                    arrayOperaciones.length++;
                    arrayOperaciones[arrayOperaciones.length - 1] = $(this).attr('tipo');
                });

                var resultadoideal = parseFloat(arrayVariables[0]);
                for (var i = 1; i < arrayVariables.length; i++) {
                    if (arrayOperaciones[i - 1] == '+')
                        resultadoideal += parseFloat(arrayVariables[i]);
                    else if (arrayOperaciones[i - 1] == '-')
                        resultadoideal -= parseFloat(arrayVariables[i]);
                    else if (arrayOperaciones[i - 1] == '*')
                        resultadoideal = resultadoideal * parseFloat(arrayVariables[i]);
                    else if (arrayOperaciones[i - 1] == '/')
                        resultadoideal = resultadoideal / parseFloat(arrayVariables[i]);
                }

                document.getElementById("resultado").innerHTML += '<br/><span class=\'titulo4\'>' + $(this).find('ideal_titulo').text() + ':</span>&nbsp;<span class=\'titulo5\'>' + resultadoideal.toFixed(2).replace('.', ',') + '</span>&nbsp;<span class=\'titulo4_unidad\'>' + $(this).attr('unidad') + '</span>';
            }
        });
    }
	
	if (id=='hep3'){
		resultado = hep3; 	
		 if (document.getElementById("resultado").innerHTML != '')
        document.getElementById("resultado").innerHTML = '<div class=\'t_white\'><div class=\'b_white\'><div class=\'l_white\'><div class=\'r_white\'><div class=\'bl_white\'><div class=\'br_white\'><div class=\'tl_white\'><div class=\'tr_white\'>' + resultado + '</div></div></div></div></div></div></div></div>';
	}else{
		 if (document.getElementById("resultado").innerHTML != '')
        document.getElementById("resultado").innerHTML = '<div class=\'t_white\'><div class=\'b_white\'><div class=\'l_white\'><div class=\'r_white\'><div class=\'bl_white\'><div class=\'br_white\'><div class=\'tl_white\'><div class=\'tr_white\'>' + document.getElementById("resultado").innerHTML + '</div></div></div></div></div></div></div></div>';
	} 
	
   
}

function radioSelected(radio, opcion, formula) {
	if (radio=='c0' && formula=='gas3'){
			modificar_gas3(opcion);
	}

    if (radio=='c1' && formula=="hep3"){
        editValuesHep3(opcion);
    }
    

  for (i = 0; i < radio.length; i++)
        if (eval('document.calcform.' + radio + '[' + i + ']') && eval('document.calcform.' + radio + '[' + i + '].value') != opcion)
            eval('document.calcform.' + radio + '[' + i + '].checked = false');
        else if(eval('document.calcform.' + radio + '[' + i + '].value') == opcion)
        {
            eval('document.calcform.' + radio + '[' + i + '].checked = true');
        }
    }

function getRadioValue(radio) {        
    for (i = 0; i < radio.length; i++)
        if (eval('document.calcform.' + radio + '[' + i + ']') && eval('document.calcform.' + radio + '[' + i + '].checked'))
            return eval('document.calcform.' + radio + '[' + i + '].value');
}

function calcular_epi3() {

    if (document.getElementById('var1').value.replace(',', '.') == '' || document.getElementById('var2').value.replace(',', '.') == '' || document.getElementById('var3').value.replace(',', '.') == '' || document.getElementById('var4').value.replace(',', '.') == '') {
        alert('Los datos de cálculos sobre la muestra del estudio son obligatorios.');
        return;
    }

    document.getElementById("epi3_resultado").style.visibility = "visible";
    document.getElementById("epi3_resultado").style.height = "530px";

    var r1 = (parseFloat(document.getElementById('var1').value.replace(',', '.')) + parseFloat(document.getElementById('var3').value.replace(',', '.'))) / (parseFloat(document.getElementById('var1').value.replace(',', '.')) + parseFloat(document.getElementById('var2').value.replace(',', '.')) + parseFloat(document.getElementById('var3').value.replace(',', '.')) + parseFloat(document.getElementById('var4').value.replace(',', '.')));
    var r2 = parseFloat(document.getElementById('var1').value.replace(',', '.')) / (parseFloat(document.getElementById('var1').value.replace(',', '.')) + parseFloat(document.getElementById('var3').value.replace(',', '.')));
    var r3 = parseFloat(document.getElementById('var4').value.replace(',', '.')) / (parseFloat(document.getElementById('var2').value.replace(',', '.')) + parseFloat(document.getElementById('var4').value.replace(',', '.')));
    var r4 = parseFloat(document.getElementById('var1').value.replace(',', '.')) / (parseFloat(document.getElementById('var1').value.replace(',', '.')) + parseFloat(document.getElementById('var2').value.replace(',', '.')));
    var r5 = parseFloat(document.getElementById('var4').value.replace(',', '.')) / (parseFloat(document.getElementById('var3').value.replace(',', '.')) + parseFloat(document.getElementById('var4').value.replace(',', '.')));
    var r6 = r2 / (1 - r3);
    var r7 = (1 - r2) / r3;
    
    document.getElementById('r1').innerHTML = (r1).toFixed(2).replace('.', ',');
    document.getElementById('r2').innerHTML = (r2).toFixed(2).replace('.', ',');
    document.getElementById('r3').innerHTML = (r3).toFixed(2).replace('.', ',');
    document.getElementById('r4').innerHTML = (r4).toFixed(2).replace('.', ',');
    document.getElementById('r5').innerHTML = (r5).toFixed(2).replace('.', ',');
    document.getElementById('r6').innerHTML = (r6).toFixed(2).replace('.', ',');
    document.getElementById('r7').innerHTML = (r7).toFixed(2).replace('.', ',');

    if (document.getElementById('var5').value.replace(',', '.') == '') {
        document.getElementById('r8').innerHTML = '-';
        document.getElementById('r9').innerHTML = '-';
        document.getElementById('r10').innerHTML = '-';
        document.getElementById('r11').innerHTML = '-';
        document.getElementById('r12').innerHTML = '-';
    }
    else {
        var r8 = parseFloat(document.getElementById('var5').value.replace(',', '.')) / (1 - parseFloat(document.getElementById('var5').value.replace(',', '.')));
        var r9 = r8 * r6;
        var r10 = r8 * r7;
        var r11 = r9 / (r9 + 1);
        var r12 = r10 / (r10 + 1);

        document.getElementById('r8').innerHTML = (r8).toFixed(2).replace('.', ',');
        document.getElementById('r9').innerHTML = (r9).toFixed(2).replace('.', ',');
        document.getElementById('r10').innerHTML = (r10).toFixed(2).replace('.', ',');
        document.getElementById('r11').innerHTML = (r11).toFixed(2).replace('.', ',');
        document.getElementById('r12').innerHTML = (r12).toFixed(2).replace('.', ',');
    }
    
}

function calcular_hep3(){

	//Al no ser obligatorio 'sódio sérico' hay que inicializarlo a 0, para que funcione la formula
	//A la hora de diseñar esta calculadora hay que tener en cuenta que los valores menores de 1 deben igualarse a 1 y que el valor máximo de creatinina puede ser 4 mg/dl.
	if (jQuery('#inputvariables #v4').val()=='') jQuery('#inputvariables #v4').val('0'); 
	if (jQuery('#inputvariables #v1').val()<1) jQuery('#inputvariables #v1').val(1);
	if (jQuery('#inputvariables #v2').val()<1) jQuery('#inputvariables #v2').val(1);
	if (jQuery('#inputvariables #v3').val()<1) jQuery('#inputvariables #v3').val(1);
	if (jQuery('#inputvariables #v3').val()>4) jQuery('#inputvariables #v3').val(4);
	if (jQuery('#inputvariables #v4').val()!='0' && jQuery('#inputvariables #v4').val()<1) jQuery('#inputvariables #v4').val(1);

	/*		MELD Score = 9,6 Ln(Creat)+3,8 Ln(Br) + 11,2 Ln(INR) + 6,4
			MELD Na Score= MELD - Na-(0.025*MELD*(140-Na))+140 
	*/
	
	var meld;
	var meld_na;

	meld = (9.6 * Math.log(document.getElementById('v3').value) + 3.8 * Math.log(document.getElementById('v2').value) + 11.2 *  Math.log(document.getElementById('v1').value) ) + 6.4;
	meld_na = meld - document.getElementById('v4').value - (0.025*meld*(140-document.getElementById('v4').value)) +140;

	meld = Math.round(meld);
	meld_na = Math.round(meld_na);

	if (meld_na > 40) meld_na = 40;
	var txt_interpretacion = '';
	if (meld <= 14){
		txt_interpretacion ='En los pacientes con MELD <=14, la supervivencia a 1 año es menor tras el trasplante que sin trasplante.';	
	}else if (meld >= 15){
		txt_interpretacion ='Una puntuación MELD >=15 indica recomendación de incluir al paciente en lista de trasplante hepáticos.';	
	} 

	return '<br /><span class="titulo5">MELD: &nbsp;' + meld + '</span><br /><span class="titulo5"> MELD-Na: &nbsp;' + meld_na + '</span><br />&nbsp; <span class="titulo4_unidad">' + txt_interpretacion + '</span>' ;
}

function calcular_gas6(){
    
    //alert(getRadioValue(jQuery('#inputvariables #c1')));
    var radioValuesMenores = '';
    var radioValuesMayorA = '';
    var radioValuesMayorB = '';

     jQuery('input[type="radio"]:checked').each(function() {
        if (jQuery(this).val()!=0){
            if (jQuery(this).val() == 'menor') radioValuesMenores += jQuery(this).val() + ',';
            if (jQuery(this).val() == 'mayorA') radioValuesMayorA += jQuery(this).val() + ',';
            if (jQuery(this).val() == 'mayorB') radioValuesMayorB += jQuery(this).val() + ',';
        }
    });

    var valor_menor = (radioValuesMenores.split(",").length - 1);
    var valor_mayorA = (radioValuesMayorA.split(",").length - 1);
    var valor_mayorB = (radioValuesMayorB.split(",").length - 1);
    
    /*console.log('Menor = ' + valor_menor);
    console.log(' MayorA = ' + valor_mayorA);
    console.log(' MayorB = ' + valor_mayorB);*/
    //1 mayor A y 1 mayor B ó 2 mayores A. 
    var reultado = '';
    if (valor_menor < 3 && valor_mayorA==0 && valor_mayorB ==0) resultado = 'Normal';
    if (valor_menor >= 3 && valor_menor <=4 && valor_mayorA==0 && valor_mayorB ==0) resultado = 'Indeterminada';
    if (valor_menor <3 && valor_mayorB ==1) resultado = 'Indeterminada';
    if (valor_menor <3 && valor_mayorA ==1) resultado = 'Sugestiva';
    if (valor_menor >=3 && valor_mayorB ==1) resultado = 'Sugestiva';
    if (valor_menor >=5) resultado = 'Sugestiva';
    if (valor_menor >=3 && valor_mayorA==1) resultado = 'Diagnóstica';
    if (valor_mayorA ==1 && valor_mayorB==1) resultado = 'Diagnóstica';
    if (valor_mayorA==2) resultado = 'Diagnóstica';
    
    //console.log(resultado);
    jQuery('#resultado').addClass('showed');

   // jQuery('#resultado').html('<span class="titulo4">Resultado:&nbsp;</span><span class="titulo5">' + resultado + '</span>');
  jQuery('#resultado').html('<div class=\'t_white\'><div class=\'b_white\'><div class=\'l_white\'><div class=\'r_white\'><div class=\'bl_white\'><div class=\'br_white\'><div class=\'tl_white\'><div class=\'tr_white\'><span class="titulo4">Resultado:&nbsp;</span><span class="titulo5">' + resultado + '</span></div></div></div></div></div></div></div></div>');  
    
}

function modificar_gas3(val){
	if (val==0){
		jQuery("#inputvariables #gas3_span_1").html('Edad > a 55 años');
		jQuery("#inputvariables #gas3_span_2").html('Hemoglobina > a 16 g/L');
		jQuery("#inputvariables #gas3_span_3").html('Glucemia > a 200 mg/dL');
		jQuery("#inputvariables #gas3_span_4").html('LDH > a 350 UI/L');
		jQuery("#inputvariables #gas3_span_10").html('Déficit de bases > a 4 mEq/L');
		jQuery("#inputvariables #gas3_span_11").html('Necesidades de líquidos > a 6 litros');
	}else{
		jQuery("#inputvariables #gas3_span_1").html('Edad > a 70 años');
		jQuery("#inputvariables #gas3_span_2").html('Hemoglobina > a 18 g/L');
		jQuery("#inputvariables #gas3_span_3").html('Glucemia > a 220 mg/dL');
		jQuery("#inputvariables #gas3_span_4").html('LDH > a 400 UI/L');
		jQuery("#inputvariables #gas3_span_10").html('Déficit de bases > a 5 mEq/L');
		jQuery("#inputvariables #gas3_span_11").html('Necesidades de líquidos > a 4 litros');
	}
 }

function editValuesHep3(val){
    //Si se marca la opción SI a la pregunta ¿El paciente se ha sometido a diálisis reciente? (al menos 2 veces en la última semana): si/no, el valor de creatinina debe sustituirse por 4 mg/dl.
    jQuery('#inputvariables #v3').val('4');
    if (val==0){
        jQuery('#inputvariables #v3').val('');
    }
}
