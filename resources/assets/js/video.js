// Fichero: video.js
// Autor: Xavi Baz
// Fecha: 4 sept 2018
// Entorno: Global, paquete principal
// Descripción: - Funciones para gestión de reproducción de videos

// arrancar la visualización de un vídeo
export default function playVideo(data) {

    // crear el reproductor, según el tipo de vídeo
    establecerPlayer(data.tipo, data.url, data.autoplay);
    establecerDescripciones(data.cabecera, data.titulo, data.subtitulo, data.descripcion);
    mostrarFlotante();
    
    // si es SEPDTV debe contabilizar una nueva reproducción
    if (data.tipo == 2) {
        // recuperar el código del vídeo
        var codigo = data.url.substring(data.url.lastIndexOf('/')+1, data.url.lastIndexOf('.'));
        // llamada api para sumar reproducción; no se controla si ha ido bien o no
        axios.post('/api/reproduccion/' + codigo, {
            video: codigo
        });

    }

}

// montar el reproductor:
// youtube -> iframe
// SEPD TV -> video HTML tag
function establecerPlayer(tipo, enlace, autoplay = 0) {

    var htmlPlayer = '';
    var iniciar = '';

    switch (tipo) {
        case 1:
            (autoplay == 1) ? iniciar = "&autoplay=1" : iniciar = '';
            htmlPlayer = '<iframe class="embed-responsive-item" src="' + enlace + '?rel=0&showinfo=0'+ iniciar +'" allowfullscreen></iframe>';
            break;
        case 2:
            (autoplay == 1) ? iniciar = " autoplay" : iniciar = '';
            htmlPlayer = '<video controls="controls" class="embed-responsive-item"'+ iniciar +'><source src="' + enlace + '" /></video>';
            break;
    }
    
    $('#modalVideoPlayer').html(htmlPlayer);

}

// Textos correspondientes al vídeo
function establecerDescripciones(cabecera, titulo, subtitulo, descripcion) {
    
    $('#modalVideoTitulo').html(cabecera);
    $('#modalVideoDescripcion .video-titulo').html(titulo);
    if (subtitulo != '') {
        $('#modalVideoDescripcion .video-subtitulo').html(subtitulo);
        $('#modalVideoDescripcion .video-subtitulo').removeClass('d-none');
    } else {
        $('#modalVideoDescripcion .video-subtitulo').html('');
        $('#modalVideoDescripcion .video-subtitulo').addClass('d-none');
    }
    $('#modalVideoDescripcion .video-descripcion').html(descripcion);
    
}

// Mostrar la flotante con el vídeo
// Al cerrar la flotante se debe parar el vídeo
function mostrarFlotante() {

    $('#modalVideo').modal('show');
    // programar el "stop" al cerrar la modal
    $("#modalVideo").on('hide.bs.modal', function(){
        $("#modalVideoPlayer").html('');
    });

}

