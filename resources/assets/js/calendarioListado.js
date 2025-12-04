// Fichero: prensa.js
// Autor: Xavi Baz
// Fecha: 24 julio 2018
// Entorno: Página de repercusión mediática, con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar lista al hacer click o Enter en algún filtro

// Cargar el componente VUE y crear la instancia VUE
import CalendarioIndex from './components/calendario/CalendarioIndex.vue';


window.instanciaVue = new Vue({
    el: '#contenidoVue',
    components: { CalendarioIndex },
    methods: {
            // cargar las notas de prensa de inicio y/o con filtros
            refrescarEventos: function() {
                this.$refs.CalendarioIndex.getEventos();
            },
             getEvento: function(id) {
                this.$refs.CalendarioIndex.getEvento(id);
            }
    }
});

///////////
// function getListaFiltrosActivos() en FILTROS.JS
///////////

// document ready => programar eventos
$(document).ready(function() {

    // captar los filtros, menos en el buscador
    $("#formFiltros").click(function( event ) {
        if (event.target.nodeName == 'INPUT' && event.target.name !== undefined && event.target.name !== 'search') {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarEventos();
        }
    });

    // captar el enter en search
    $('#formFiltros input[name="search"]').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarEventos();
            event.preventDefault();
        }
    });
    
    $('body').on('click', '.fc-event-container a', function (event){
        event.preventDefault();
        var id = $(this).attr('href');
        instanciaVue.getEvento(id);       
    });
    
    // Iniciar la lista de eventos
    // Comprobar si llega algo en la url: calendario/id
    var params = window.location.pathname.split( '/' );
    if (params.length == 3) {
        console.log(params[2]);
        instanciaVue.getEvento(params[2]);
    } else {
        $('#filtrosGet').val(getListaFiltrosActivos());
        instanciaVue.refrescarEventos($('#paginaGet').val());
    }

});
