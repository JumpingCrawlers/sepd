// Fichero: sepdtv.js
// Autor: Martin Nikolaev
// Fecha: 10 Septiembre 2018
// Entorno: Página de repercusión mediática, con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar lista al hacer click o Enter en algún filtro


// Cargar el componente VUE y crear la instancia VUE
import SepdtvIndex from './components/sepdtv/SepdtvIndex.vue';
// import SepdtvIndex from './components/sepdtv/SepdtvIndex.vue';
import SepdtvVideo from './components/sepdtv/SepdtvVideo.vue';


/* La primera instancia es para mostrar la lista con los videos */
window.instanciaVue = new Vue({
    el: '#contenidoVue',
    components: { SepdtvIndex },
    methods: {
        // cargar las sepdtv de inicio y/o con filtros
        refrescarSepdtv: function() {
            this.$refs.SepdtvIndex.getSepdtv();
        }
    }
});

/* Se crea la segunda instancia para mostrar el video seleccionado */
window.instanciaVue2 = new Vue({
    el: '#contenidoVue2',
    components: { SepdtvVideo },
    methods: {
            refrescarSepdtv: function() {
                this.$refs.SepdtvVideo.getSepdtv();
            },
            showVideo: function(codigo, titulo, subtitulo, descripcion, contador) {
                this.$refs.SepdtvVideo.playVideo(codigo, titulo, subtitulo, descripcion, contador);
            }
    }
});

// Comprobación de si es un touch_device
window.is_touch_device = function() {
  var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
  var mq = function(query) {
    return window.matchMedia(query).matches;
  }

  if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
    return true;
  }

  // include the 'heartz' as a way to have a non matching MQ to help terminate the join
  // https://git.io/vznFH
  var query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
  return mq(query);
}

// document ready => programar eventos
$(document).ready(function() {

    // captar los filtros, menos en el buscador
    $("#formFiltros").click(function( event ) {
        if (event.target.nodeName == 'INPUT' && event.target.name !== undefined && event.target.name !== 'search') {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarSepdtv();
        }
    });

    $("#reset-filter-areas").click(function( event ) {
        $('#filtroAreas input[type="checkbox"]').prop('checked', false);
        var listaParametros = getListaFiltrosActivos();
        $('#filtrosGet').val(listaParametros);
        instanciaVue.refrescarSepdtv();
    });

    // captar el enter en search "sepdtv"
    $('#formFiltros input[name="search"]').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarSepdtv();
            event.preventDefault();
        }
    });

    // Iniciar la lista de sepdtv
    $('#filtrosGet').val(getListaFiltrosActivos());
    instanciaVue.refrescarSepdtv($('#paginaGet').val());
    instanciaVue2.refrescarSepdtv($('#paginaGet').val());

    // Función para pasar los datos de la vista de la lista a la vista del video y mostrarlo
    $("body").on('click', '.pointer', function( e ) {
        if (is_touch_device()) {
            $('html, body').animate({
              scrollTop: ($("#videoSepdtv").offset().top) - 15
            }, 1000);
        }
        
        const new_path = ('/sepdtv/video/' + e.currentTarget.dataset.codigo);
        if (location.pathname == new_path) return;

        history.pushState(null, document.title, new_path);
        instanciaVue2.showVideo(e.currentTarget.dataset.codigo, e.currentTarget.dataset.titulo, e.currentTarget.dataset.subtitulo, e.currentTarget.dataset.descripcion, e.currentTarget.dataset.contador);
    });

    window.addEventListener('popstate', function() {
        location.reload();
    });

    /* Marcar la opción seleccionada de destacados (area de los vídeos) como "activo" */
    var area = window.location.pathname.split('/')[2];
    if (area === undefined) {
        area= "todos";
    }
    
    var element = document.getElementById(area);
    if (element)
        element.classList.add("activo");
    
    // programar el play del vídeo
    setTimeout(function(){
        $('video[id^="instanciaVideo"]').on('play', function() {
            checkReproducciones($(this).attr('id'));
        });
    }, 1000);
    
    // y limpiar el Local Storage para la lista de vídeos reproducidos (ver checkReproducciones)
    sessionStorage.setItem('listaVideosReproducidos', JSON.stringify([]));

});

/**
 * Función de control de reproducciones
 * 
 * Debe sumar una reproducción cada vez que se arranca un vídeo.
 * Por cada carga de la página sepdtv, suma solo una vez
 * para evitar sumar reproducciones al pausar/reanudar la ejecución de un vídeo.
 * 
 * Se almacena en local storage una lista de vídeos que se han ejecutado
 * Si el vídeo no está en la lista, se añade y suma una visualización.
 * 
*/ 
function checkReproducciones(codigo) {
    
    // quitar instanciaVideo de codigo (14 caracteres)
    codigo = codigo.substr(14);
    
    let listaVideos = [];
    var sumarReproduccion = true;
    
    // recuperar la lista de vídeos reproducidos
    listaVideos = JSON.parse(sessionStorage.getItem('listaVideosReproducidos'));
    // comprobar la lista de vídeos reproducidos
    listaVideos.forEach(video => {
        // si ya estaba en la lista no se suma la reproduccion
        (video == codigo) && (sumarReproduccion = false);
    });
    if (sumarReproduccion) {
        // guardar en el array
        listaVideos.push(codigo);
        // llamada api para sumar reproducción
        // no se controla si ha ido bien o no
        axios.post('/api/reproduccion/' + codigo, {
            video: codigo
        });
    }
    // guardar la lista procesada y/o actualizada
    sessionStorage.setItem('listaVideosReproducidos', JSON.stringify(listaVideos));
    
}

