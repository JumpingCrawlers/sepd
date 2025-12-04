// Fichero: proyectos.js
// Autor: Xavi Baz
// Fecha: 22 nov 2018
// Entorno: Página de consulta de proyectos, con filtros (Vue.js)
// Descripción: - Cargar la instancia Vue con lo necesario
//              - Arranca en el document.ready
//              - Refrescar proyectos al hacer click o Enter en algún filtro


// Cargar el componente VUE y crear la instancia VUE
import ProyectosIndex from './components/proyectos/ProyectosIndex.vue';

window.instanciaVue = new Vue({
    el: '#contenidoVue',
    components: { ProyectosIndex },
    methods: {
            // cargar los proyectos de inicio y/o con filtros
            refrescarProyectos: function() {
                this.$refs.ProyectosIndex.getProyectos();
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
            instanciaVue.refrescarProyectos();
        }
    });

    $("#reset-filter-areas").click(function( event ) {
        console.log('reset-filter-areas');
        $('#filtroAreas input[type="checkbox"]').prop('checked', false);
        var listaParametros = getListaFiltrosActivos();
        $('#filtrosGet').val(listaParametros);
        instanciaVue.refrescarProyectos();
    });

    // captar el enter en search "Proyectos"
    $('#formFiltros input[name="search"]').on("keypress", function(e) {
        if (e.keyCode == 13) {
            var listaParametros = getListaFiltrosActivos();
            $('#filtrosGet').val(listaParametros);
            instanciaVue.refrescarProyectos();
            event.preventDefault();
        }
    });

    // Iniciar la lista de Proyectos
    $('#filtrosGet').val(getListaFiltrosActivos());
    instanciaVue.refrescarProyectos($('#paginaGet').val());

});