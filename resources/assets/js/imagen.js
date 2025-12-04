// Fichero: imagen.js
// Autor: Xavi Baz
// Fecha: 15 oct 2018
// Entorno: Global, paquete principal
// Descripción: - Funciones para gestión del pop up de imagen
//
/////////////////////////////////////////////////////////////////////////////////
// ATENCIÓN: similar a video.js => FUSIONAR AMBOS en un solo fichero/función
/////////////////////////////////////////////////////////////////////////////////

// carga y muestra una imagen
export default function showImagen(url, id) {

    // cargar la url de la imagen
    establecerImagen(url);
    // las descripciones si tiene...
    establecerDescripciones(id);
    // y mostrar la modal
    mostrarImagen();

}

// cargar la imagen
function establecerImagen(url) {

    var htmlImg = '';

    htmlImg = '<img class="img-fluid" src="' + url + '" />';
    
    $('#modalShowImagen').html(htmlImg);

}

// Textos correspondientes a la imagen
function establecerDescripciones(id) {
    
    // recuperar descripciones del elemento a mostrar (id)
    // Cabecera: -> Título || Subtítulo || Visor de imágenes
    $('#modalImagenTitulo').html(
            $('#' + id + ' .contenido-titulo-enlace').html() || 
            $('#' + id + ' .contenido-subtitulo-enlace').html() || 
            'Visor de imágenes'
    );
    // Título u oculto
    $('#modalImagenDescripcion .imagen-titulo').html($('#' + id + ' .contenido-titulo-enlace').html());
    if ($('#modalImagenDescripcion .imagen-titulo').html() != '') {
        $('#modalImagenDescripcion .imagen-titulo').removeClass('d-none');
    } else {
        $('#modalImagenDescripcion .imagen-titulo').addClass('d-none');
    }
    // Subtítulo u oculto
    $('#modalImagenDescripcion .imagen-subtitulo').html($('#' + id + ' .contenido-subtitulo-enlace').html());
    if ($('#modalImagenDescripcion .imagen-subtitulo').html() != '') {
        $('#modalImagenDescripcion .imagen-subtitulo').removeClass('d-none');
    } else {
        $('#modalImagenDescripcion .imagen-subtitulo').addClass('d-none');
    }
    // Descripción u oculto
    $('#modalImagenDescripcion .imagen-descripcion').html($('#' + id + ' .contenido-descripcion-enlace').html());
    if ($('#modalImagenDescripcion .imagen-descripcion').html() != '') {
        $('#modalImagenDescripcion .imagen-descripcion').removeClass('d-none');
    } else {
        $('#modalImagenDescripcion .imagen-descripcion').addClass('d-none');
    }
    
}

// Mostrar la flotante con la imagen
// Al cerrar la flotante eliminar la 
function mostrarImagen() {

    $('#modalImagen').modal('show');
    // vaciar la imagen
    $("#modalImagen").on('hide.bs.modal', function(){
        $("#modalShowImagen").html('');
    });

}

