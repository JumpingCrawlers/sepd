// Fichero: noticias.js
// Autor: Xavi Baz
// Fecha: 24 julio 2018
// Entorno: Página de repercusión mediática, con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar lista al hacer click o Enter en algún filtro

// Cargar el componente VUE y crear la instancia VUE
import NoticiaIndex from './components/noticias/NoticiaIndex.vue';

window.instanciaVue = new Vue({
    el: '#contenidoVue',
    components: { NoticiaIndex },
    methods: {
            // cargar las noticias de inicio y/o con filtros
            refrescarNoticia: function() {
                this.$refs.NoticiaIndex.getNoticia();
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
            instanciaVue.refrescarNoticia();
        }
    });

    // captar el enter en search "noticia"
    $('#formFiltros input[name="search"]').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarNoticia();
            event.preventDefault();
        }
    });

    // Iniciar la lista de noticias
    $('#filtrosGet').val(getListaFiltrosActivos());
    instanciaVue.refrescarNoticia($('#paginaGet').val());

});