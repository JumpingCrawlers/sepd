// Fichero: cursos.js
// Autor: Xavi Baz
// Fecha: 4 mayo 2018
// Entorno: Página de consulta de cursos, con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar cursos al hacer click o Enter en algún filtro


// Cargar el componente VUE y crear la instancia VUE
import CursosIndex from './components/cursos/CursosIndex.vue';

window.instanciaVue = new Vue({
    el: '#contenidoVue',
    components: { CursosIndex },
    methods: {
        // cargar los cursos de inicio y/o con filtros
        refrescarCursos: function() {
            this.$refs.CursosIndex.getCursos();
        }
    }
});


// Recorrer el formulario montando la lista de filtros a utilizar en la llamada
// Al mismo tiempo procesar si cada "grupo" está activo o no
// function getListaFiltrosActivos() en FILTROS.JS
    

$(document).ready(function() {

    // captar los filtros
    $("#formFiltros").click(function( event ) {
        if (event.target.nodeName == 'INPUT' && event.target.name !== undefined && event.target.name !== 'search') {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarCursos();
        }
    });

    $("#reset-filter-areas").click(function( event ) {
        $('#filtroAreas input[type="checkbox"]').prop('checked', false);
        var listaParametros = getListaFiltrosActivos();
        $('#filtrosGet').val(listaParametros);
        instanciaVue.refrescarCursos();
    });

    // captar el enter en search "cursos"
    $('#formFiltros input[name="search"]').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarCursos();
            event.preventDefault();
        }
    });

    // Iniciar la lista de cursos
    $('#filtrosGet').val(getListaFiltrosActivos());
    instanciaVue.refrescarCursos($('#paginaGet').val());

});