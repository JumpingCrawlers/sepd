// Fichero: galeria.js
// Autor: Martin Nikolaev
// Fecha: 12 septiembre 2018
// Entorno: Página de galeria, con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar lista al hacer click o Enter en algún filtro

// Cargar el componente VUE y crear la instancia VUE
import GaleriaIndex from './components/galeria/GaleriaIndex.vue';

window.instanciaVue = new Vue({
    el: '#contenidoVue',
    components: { GaleriaIndex },
    methods: {
            // cargar las notas de galeria de inicio y/o con filtros
            refrescarGaleria: function() {
                this.$refs.GaleriaIndex.getGaleria();
            }
    }
});

///////////
// function getListaFiltrosActivos() en FILTROS.JS
///////////

// document ready => programar eventos
$(document).ready(function() {

    localStorage.clear();

    // captar los filtros, menos en el buscador
    $("#formFiltros").click(function( event ) {
        if (event.target.nodeName == 'INPUT' && event.target.name !== undefined && event.target.name !== 'search') {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarGaleria();
        }
    });

    // captar el enter en search "galeria"
    $('#formFiltros input[name="search"]').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarGaleria();
            event.preventDefault();
        }
    });

    // Iniciar la lista de galeria
    $('#filtrosGet').val(getListaFiltrosActivos());
    instanciaVue.refrescarGaleria($('#paginaGet').val());

});