// Fichero: dossier.js
// Autor: Xavi Baz
// Fecha: 17 mayo 2018
// Entorno: Página de repercusión mediática, con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar lista al hacer click o Enter en algún filtro

// Cargar el componente VUE y crear la instancia VUE
import DossierIndex from './components/dossier/DossierIndex.vue';

window.instanciaVue = new Vue({
    el: '#contenidoVue',
    components: { DossierIndex },
    methods: {
            // cargar los cursos de inicio y/o con filtros
            refrescarDossier: function() {
                this.$refs.DossierIndex.getDossier();
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
            instanciaVue.refrescarDossier();
        }
    });

    // captar el enter en search "dossier"
    $('#formFiltros input[name="search"]').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarDossier();
            event.preventDefault();
        }
    });

    // Iniciar la lista de dossier
    $('#filtrosGet').val(getListaFiltrosActivos());
    instanciaVue.refrescarDossier($('#paginaGet').val());

});