// Fichero: prensa.js
// Autor: Xavi Baz
// Fecha: 24 julio 2018
// Entorno: Página de repercusión mediática, con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar lista al hacer click o Enter en algún filtro

// Cargar el componente VUE y crear la instancia VUE
import PrensaIndex from './components/prensa/PrensaIndex.vue';

window.instanciaVue = new Vue({
    el: '#contenidoVue',
    components: { PrensaIndex },
    methods: {
            // cargar las notas de prensa de inicio y/o con filtros
            refrescarPrensa: function() {
                this.$refs.PrensaIndex.getPrensa();
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
            instanciaVue.refrescarPrensa();
        }
    });

    // captar el enter en search "prensa"
    $('#formFiltros input[name="search"]').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarPrensa();
            event.preventDefault();
        }
    });

    // Iniciar la lista de prensa
    $('#filtrosGet').val(getListaFiltrosActivos());
    instanciaVue.refrescarPrensa($('#paginaGet').val());

});