/**
 * Funcion que agrega un conocimientos a la lista con todos sus elementos.
 *
 * @return void
 */
function agregar_conocimiento(element) {
    var lista_c= element.classList;
    lista_c = lista_c[1]+"_panel"
    var lista_conocimientos = document.querySelector("."+lista_c)
    var conocimiento = create_text(lista_c);
    lista_conocimientos.insertAdjacentHTML( 'beforeend', conocimiento)
}

/**
 * Funcion que marca un elemento para ser eliminado de la bd,
 * se utiliza para eliminar conocimientos, habilidades, aptitudes
 * y meritos.
 *
 * @return void
 */
function set_for_delete(element, type, id) {
    element.value = "1"

    element.parentNode.remove();

    $("#" + type + "_to_delete").val(!$("#" + type + "_to_delete").val() ? id : ($("#" + type + "_to_delete").val() + "," + id));
    console.log($("#" + type + "_to_delete").val());
}

/**
 * Funcion que agrega un elemento de tipo input 
 * para que un usuario inserte una nueva habilidad
 *
 * @return void
 */
function agregar_input_habilidades(element) {
    var lista_c= element.classList;
    lista_c = "file_"+lista_c[2]
    var inputs = document.querySelectorAll(".file_habilidades>.wrapper_botton")
    var inputs2 = document.querySelectorAll(".file_habilidades>.show_file__wrapper>.wrapper_botton_icon")
    // Se cuentan todas las habilidades tanto las que ya existen en la base de datos como las que seran agregadas
    if (inputs !== null && inputs2 !== null) {
        var len = inputs.length + inputs2.length ;
    }
    else if(inputs2 !== null)
    {
        var len = inputs2.length ;
    }
    else if(inputs !== null)
    {
        var len = inputs.length ;
    }
    else{
        var len = 0
    }
    var file_numbers = document.querySelector(".file_habilidades>.files_number")
    file_numbers.value = parseInt(file_numbers.value) + 1
    var lista_conocimientos = document.querySelector("."+lista_c)

    /** En caso que un usuario cree y elimine habilidades sin insertarlas en la base de datos
     *  se debe recorrer el documento buscando el minimo id entre las habilidades nuevas que se puede agregar.
     * Ejemplo: si el usuario crea 3 habilidades con sus Id's 1, 2, 3 y borra 2 la proxima habilidad a crear es 2 no 4.
     * */ 
    while (document.getElementById('inputH'+ (len + 1) +'') != null) {
        len = len - 1
    }
    var conocimiento = 
    '<div id="inputH'+ (len + 1) +'" class="wrapper_botton habilidad_agregada">'+
        '<div class="trash__icon__file white cursor-normal fz-17">'+
            (len + 1)+'.'+
        '</div>'+
        '<div class="input-group mb-3">'+
            '<div class="custom-file">'+
                '<input onchange="change_label(this)" type="file" name="hfileToUpload'+ (len + 1) +'" class="custom-file-input" id="hfileToUpload'+ (len + 1) +'" aria-describedby="inputGroupFileAddon03" />'+
                '<label class="custom-file-label" id="label_hfileToUpload'+ (len + 1) +'" for="hfileToUpload'+ (len + 1) +'">Ningun archivo seleccionado</label>'+
            '</div>'+
        '</div>'+
        '<button type="button" class="btn trash__icon__file btn-red" onclick="eliminarh(inputH'+ (len + 1) +');">' +
            '<i class="fa fa-trash fa-1x" aria-hidden="true"></i>' +
            '<input value="' + (len + 1) + '" type="hidden" name="habilidad_' + (len + 1) + '">'+
        '</button>'+
    '</div>'
    
    lista_conocimientos.insertAdjacentHTML( 'beforeend', conocimiento)
}

/**
 * Funcion que agrega un elemento de tipo input 
 * para que un usuario inserte una nueva aptitud
 *
 * @return void
 */
function agregar_input_aptitudes(element) {

    if (typeof document.getElementsByName("afileToUpload1")[0] !== 'undefined') {
        console.log(document.getElementsByName("afileToUpload1")[0].value)
    }
    var lista_c= element.classList;
    lista_c = "file_"+lista_c[2]
    var inputs = document.querySelectorAll(".file_aptitudes>.wrapper_botton")
    var inputs2 = document.querySelectorAll(".file_aptitudes>.show_file__wrapper>.wrapper_botton_icon")
    // Se cuentan todas las aptitudes tanto las que ya existen en la base de datos como las que seran agregadas
    if (inputs !== null && inputs2 !== null) {
        var len = inputs.length + inputs2.length ;
    }
    else if(inputs2 !== null)
    {
        var len = inputs2.length ;
    }
    else if(inputs !== null)
    {
        var len = inputs.length ;
    }
    else{
        var len = 0
    }
    var file_numbers = document.querySelector(".file_aptitudes>.files_number_aptitud")
    file_numbers.value = parseInt(file_numbers.value) + 1
    var lista_conocimientos = document.querySelector("."+lista_c)

    /** En caso que un usuario cree y elimine aptitudes sin insertarlas en la base de datos
     *  se debe recorrer el documento buscando el minimo id entre las aptitudes nuevas que se puede agregar.
     * Ejemplo: si el usuario crea 3 aptitudes con sus Id's 1, 2, 3 y borra 2 la proxima habilidad a crear es 2 no 4.
     * */ 
    while (document.getElementById('inputA'+ (len + 1) +'') != null) {
        len = len - 1
    }

    var conocimiento = 
    '<div id="inputA'+ (len + 1) +'" class="wrapper_botton aptitud_agregada">'+
        '<div class="trash__icon__file white cursor-normal fz-17">'+
            (len + 1)+'.'+
        '</div>'+
        '<div class="input-group mb-3">'+
            '<div class="custom-file">'+
                '<input onchange="change_label(this)" type="file" name="afileToUpload'+ (len + 1) +'" class="custom-file-input" id="afileToUpload'+ (len + 1) +'" aria-describedby="inputGroupFileAddon03">'+
                '<label class="custom-file-label" id="label_afileToUpload'+ (len + 1) +'" for="afileToUpload'+ (len + 1) +'">Ningun archivo seleccionado</label>'+
            '</div>'+
        '</div>'+
        '<button type="button" class="btn trash__icon__file btn-red" onclick="eliminara(inputA'+ (len + 1) +');">' +
            '<i class="fa fa-trash fa-1x" aria-hidden="true"></i>' +
            '<input value="'+ (len + 1) +'" type="hidden" name="aptitud_'+ (len + 1) +'">'+
        '</button>'+
    '</div>';


    lista_conocimientos.insertAdjacentHTML( 'beforeend', conocimiento)
}

/**
 * Funcion que agrega un elemento de tipo input 
 * para que un usuario inserte una nuevo merito
 *
 * @return void
 */
function agregar_input_meritos(element) {
    var lista_c= element.classList;
    lista_c = "file_"+lista_c[2]
    var inputs = document.querySelectorAll(".file_meritos>.wrapper_botton")
    var inputs2 = document.querySelectorAll(".file_meritos>.show_file__wrapper>.wrapper_botton_icon")
    // Se cuentan todas las aptitudes tanto las que ya existen en la base de datos como las que seran agregadas
    if (inputs !== null && inputs2 !== null) {
        var len = inputs.length + inputs2.length ;
    }
    else if(inputs2 !== null)
    {
        var len = inputs2.length ;
    }
    else if(inputs !== null)
    {
        var len = inputs.length ;
    }
    else{
        var len = 0
    }
    var file_numbers = document.querySelector(".file_meritos>.files_number_merito")
    file_numbers.value = parseInt(file_numbers.value) + 1
    var lista_conocimientos = document.querySelector("."+lista_c)

    /** En caso que un usuario cree y elimine aptitudes sin insertarlas en la base de datos
     *  se debe recorrer el documento buscando el minimo id entre las aptitudes nuevas que se puede agregar.
     * Ejemplo: si el usuario crea 3 aptitudes con sus Id's 1, 2, 3 y borra 2 la proxima habilidad a crear es 2 no 4.
     * */ 
    while (document.getElementById('inputM'+ (len + 1) +'') != null) {
        len = len - 1
    }
    var conocimiento = 
    '<div id="inputM'+ (len + 1) +'" class="wrapper_botton merito_agregado">'+
        '<div class="trash__icon__file white cursor-normal fz-17">'+
            (len + 1)+'.'+
        '</div>'+
        '<div class="input-group mb-3">'+
            '<div class="custom-file">'+
                '<input onchange="change_label(this)" type="file" name="mfileToUpload'+ (len + 1) +'" class="custom-file-input" id="mfileToUpload'+ (len + 1) +'" aria-describedby="inputGroupFileAddon03">'+
                '<label class="custom-file-label" id="label_mfileToUpload'+ (len + 1) +'" for="mfileToUpload'+ (len + 1) +'">Ningun archivo seleccionado</label>'+
            '</div>'+
        '</div>'+
        '<button type="button" class="btn trash__icon__file btn-red" onclick="eliminara(inputM'+ (len + 1) +');">' +
            '<i class="fa fa-trash fa-1x" aria-hidden="true"></i>' +
            '<input value="'+ (len + 1) +'" type="hidden" name="merito_'+ (len + 1) +'">'+
        '</button>'+
    '</div>'
    lista_conocimientos.insertAdjacentHTML( 'beforeend', conocimiento)
}

/**
 * Funcion que elimina un conocimiento del html no la bd.
 *
 * @return void
 */
function eliminar_conocimiento(element){
    papa = element.parentNode
    abuelo  = papa.parentNode;
    abuelo.parentNode.removeChild(abuelo);
}

/**
 * Funcion que elimina un elemento del html no la bd.
 *
 * @return void
 */
function eliminar(element){
    console.log(element)
    element.parentNode.removeChild(element)   
}

/**
 * Funcion que elimina una habilidad del html no la bd.
 *
 * @return void
 */
function eliminarh(element){
    console.log(element)
    element.parentNode.removeChild(element)  
    var file_numbers = document.querySelector(".file_habilidades>.files_number")
    file_numbers.value = parseInt(file_numbers.value) - 1 
}

/**
 * Funcion que elimina una aptitud del html no la bd.
 *
 * @return void
 */
function eliminara(element){
    console.log(element)
    element.parentNode.removeChild(element)   
    var file_numbers = document.querySelector(".file_aptitudes>.files_number_aptitud")
    file_numbers.value = parseInt(file_numbers.value) - 1
}

/**
 * Funcion que elimina un merito del html no la bd.
 *
 * @return void
 */
function eliminarm(element){
    console.log(element)
    element.parentNode.removeChild(element)   
    var file_numbers = document.querySelector(".file_meritos>.files_number_merito")
    file_numbers.value = parseInt(file_numbers.value) - 1
}

/**
 * Funcion que crea el html de un conocimiento a agregar.
 *
 * @return void
 */
function create_text(lista_c) {
    var conocimientos__wrapper = document.querySelectorAll(".conocimientos__wrapper")
    if (conocimientos__wrapper !== null) {
        var len = conocimientos__wrapper.length;
    }
    else{
        var len = 0
    }

    /** En caso que un usuario cree y elimine aptitudes sin insertarlas en la base de datos
     *  se debe recorrer el documento buscando el minimo id entre las aptitudes nuevas que se puede agregar.
     * Ejemplo: si el usuario crea 3 aptitudes con sus Id's 1, 2, 3 y borra 2 la proxima habilidad a crear es 2 no 4.
     * */ 
    while (document.getElementById('conocimiento'+ (len + 1) +'') != null) {
        len = len - 1
    }

    /**
     * Creating a variable with current date that will controle the input type date
     * The input value will not be greater than the current date
     * Date format: YYYY-MM-DD
     */

    var today = new Date();
    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

    var conocimiento = 
        (
        '<div>' + 
            '<div class="conocimientos__wrapper '+ lista_c + "_item" + ' conocimiento_agregado">' +
            /*'<form onsubmit="return checkValues(this)" id="conocimiento_form" action="validar.php?action=guardar_conocimiento#conocimiento_form" method="POST" enctype="multipart/form-data">' +*/
                    '<div class="wrapper_icon">' +
                        '<div class="trash__icon white cursor-normal fz-17">'+
                            (len + 1)+'.'+
                        '</div>'+
                        '<div id="conocimiento' + (len + 1) + '" class="agregar__conocimientos_panel conocimiento'+ (len + 1) +'" onclick="mostrar_conocimiento(this);">' +
                        '    <p class="conocimientos__titulo mtconocimiento'+ (len + 1) +'">' +
                        '    </p>' +
                        '</div>' +
                        '<button type="button" class="btn trash__icon btn-red" onclick="eliminar_conocimiento(conocimiento' + (len + 1) +');">' +
                            '<i class="fa fa-trash fa-1x" aria-hidden="true"></i>' +
                        '</button>' +
                    '</div>' +
                    '<div class="conocimientos__validar conocimiento'+ (len + 1) +'_panel display__alt">' +
                    '    <div class="titulo__wrapper">' +
                    '        <p class="validar__certificados">Titulo</p>' +
                    '        <input class="stconocimiento'+ (len + 1) +' fecha" type="text" name="conocimiento'+ (len + 1) +'_titulo" data-uso="titulo" placeholder="Nombre del Titulo">' +
                    '    </div>' +
                    '    <div class="certificados__wrapper">' +
                    '        <p class="validar__certificados">Certificado</p>' +
                    '        <div class="file">' +
                                '<div class="input-group mb-1">'+
                                    '<div class="custom-file">'+
                                        '<input onchange="change_label(this)" type="file" name="conocimiento'+ (len + 1) +'_fileToUpload'+ (len + 1) +'" class="custom-file-input" id="conocimiento'+ (len + 1) +'_fileToUpload'+ (len + 1) +'" aria-describedby="inputGroupFileAddon03">'+
                                        '<label class="custom-file-label" id="label_conocimiento'+ (len + 1) +'_fileToUpload'+ (len + 1) +'" for="conocimiento'+ (len + 1) +'_fileToUpload'+ (len + 1) +'">Ningun archivo seleccionado</label>'+
                                    '</div>'+
                                '</div>'+
                                '<span id="upload-info">'+
                                    'Los tipos de archivos válidos son: <b>.jpg</b>, <b>.png</b>, <b>.jpeg</b>, <b>.gif</b>, <b>.txt</b> y <b>.pdf</b> con un tamaño máximo de <b>5MB</b>'+
                                '</span>'+
                    '        </div>' +
                    '    </div>' +
                    '    <div class="titulo__wrapper">' +
                    '        <p class="validar__titulo">Acreditado por </p>' +
                    '        <input class="titulo__input" type="text" name="conocimiento'+ (len + 1) +'_acreditador">' +
                    '    </div>' +
                    '    <div class="fecha__wrapper">' +
                    '        <div>' +
                    '            <p>Fecha Inicio</p>' +
                    '            <input class="fecha" type="date" max="'+ date +'" name="conocimiento'+ (len + 1) +'_fecha_inicio" value="dd/mm/yyyy">' +
                    '        </div>' +
                    '        <div class="fecha__box">' +
                    '            <p>Fecha Fin</p>' +
                    '            <input class="fecha" type="date" max="'+ date +'" name="conocimiento'+ (len + 1) +'_fecha_fin" value="dd/mm/yyyy">' +
                    '        </div>' +
                    '    </div>' +
                    '    <div class="valor__wrapper">' +
                    '       <p class="validar__valor">Valor</p>' +
                    '        <input class="valor__creditos" name="conocimiento'+ (len + 1) +'_valor" type="number" step="0.01">' +
                    '        <select class="valor__tipo" name="conocimiento'+ (len + 1) +'_tipo_valor" type="Selecciona archivo" value="Horas">' +
                    '            <option value="Creditos">Creditos</option>' +
                    '            <option value="Horas">Horas</option>' +
                    '        </select>' +
                    '    </div>' +
                    /*'    <div class="popup right" onclick="myFunction()">' +
                    '        <span class="popuptext" id="myPopup">Numero invalido</span>' +
                    '        <button class="competencia__guardar__alt conocimiento'+ (len + 1) +' right">Guardar</button>' +
                    '    </div>' +*/
                    '    <div class="popup right" onclick="myFunction()">' +
                    '        <span class="popuptext" id="myPopup">Numero invalido</span>' +
                    '        <button type="button" class="competencia__guardar__alt conocimiento'+ (len + 1) +' right boton_guardar_conocimiento btn btn-green">Guardar</button>' +
                    '    </div>' +
                    '</div>' +
                /*'</form>' +*/
            '</div>' +
        '</div>'
        );
        return conocimiento
}

/**
 * Funcion que cambia el label de un input 
 * para mostrar el archivo seleccionado.
 *
 * @return void
 */
function change_label(element){
    var label = document.getElementById("label_"+element.name);
    var aux = element.value.split("\\");
    label.innerText = aux[2];
}

/**
 * Funcion que se asegura que los campos en conocimiento tengan logica
 *
 * @return void
 */
function checkValues(element){
    
    var titulo = document.getElementsByName("titulo")[0].value;
    var file = document.getElementsByName("fileToUpload")[0].value;
    var acreditador = document.getElementsByName("acreditador")[0].value;
    var fecha_inicio =  Date.parse(document.getElementsByName("fecha_inicio")[0].value);
    var fecha_fin =  Date.parse(document.getElementsByName("fecha_fin")[0].value);
    var valor = parseFloat(document.getElementsByName("valor")[0].value);
    var today = Date.now();
    var sixYears = new Date();
    sixYears.setFullYear(6);

    if (titulo == "")
    {
        var popup = document.getElementById('myPopup'); 
        popup.innerText = 'El titulo no puede estar vacio'; 
        popup.classList.toggle('show');
        return false;
    }
    else if (file == "")
    {
        var popup = document.getElementById('myPopup'); 
        popup.innerText = 'Se debe cargar un archivo'; 
        popup.classList.toggle('show');
        return false;
    }
    else if (isNaN(fecha_inicio))
    {
        var popup = document.getElementById('myPopup'); 
        popup.innerText = 'La fecha de inicio no puede estar vacia'; 
        popup.classList.toggle('show');
        return false;
    }
    else if (isNaN(fecha_fin))
    {
        var popup = document.getElementById('myPopup'); 
        popup.innerText = 'La fecha de fin no puede estar vacia'; 
        popup.classList.toggle('show');
        return false;
    }
    else if (fecha_fin < fecha_inicio)
    {
        var popup = document.getElementById('myPopup'); 
        popup.innerText = 'La fecha de fin no puede ser menor a la fecha inicio'; 
        popup.classList.toggle('show');
        return false;
    }
    else if (isNaN(valor) || valor < 0)
    {
        var popup = document.getElementById('myPopup'); 
        popup.innerText = 'El valor no puede estar vacio'; 
        popup.classList.toggle('show');
        return false;
    }
    else if ( fecha_fin < sixYears )
    {
        var popup = document.getElementById('myPopup'); 
        popup.innerText = 'No se aceptan certificados con una antiguedad mayor a 6 años'; 
        popup.classList.toggle('show');
        return false;
    }
    return true;
}

/**
 * Funcion que muestra un popup de alerta.
 *
 * @return void
 */
function myFunction() {
    var popup = document.getElementById("myPopup");
    popup.classList.remove("show");
  }