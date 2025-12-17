// Fichero: biblioteca.js
// Autor: Martin Nikolaev
// Fecha: 08 octubre 2018
// Entorno: Biblioteca con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar lista al hacer click o Enter en algún filtro

// Cargar el componente VUE y crear la instancia VUE
import BibliotecaIndex from './components/biblioteca/BibliotecaIndex.vue';

window.instanciaVue = new Vue({
    el: '#contenidoVue',
    components: { BibliotecaIndex },
    methods: {
            // cargar la biblioteca y/o con filtros
            refrescarBiblioteca: function() {
                this.$refs.BibliotecaIndex.getBiblioteca();
            }
    }
});

// document ready => programar eventos
$(document).ready(function() {

    // captar los filtros, menos en el buscador
    $("#formFiltros").click(function( event ) {
        if (event.target.nodeName == 'INPUT' && event.target.name !== undefined && event.target.name !== 'search') {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarBiblioteca();
        }
    });

    $("#reset-filter-areas").click(function( event ) {
        $('#filtroAreas input[type="checkbox"]').prop('checked', false);
        var listaParametros = getListaFiltrosActivos();
        $('#filtrosGet').val(listaParametros);
        instanciaVue.refrescarBiblioteca();
    });

    // captar el enter en search "biblioteca"
    $('#formFiltros input[name="search"]').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarBiblioteca();
            event.preventDefault();
        }
    });

    // Iniciar la lista de biblioteca
    $('#filtrosGet').val(getListaFiltrosActivos());
    instanciaVue.refrescarBiblioteca($('#paginaGet').val());

    /* Marcar la opción seleccionada de destacados (area de los vídeos) como "activo" */
    var area = window.location.pathname.split('/')[2];
    if (area === undefined) {
        area= "todos";
    }
    var element = document.getElementById(area);
    element.classList.add("activo");

});