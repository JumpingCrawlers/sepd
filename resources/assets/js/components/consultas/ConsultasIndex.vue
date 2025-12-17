<template>
    <div class="container px-0" id="listaProyectos">

        <!-- Cabecera: total de consultas -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div class="row py-3 mb-3 align-items-center bg-cid">
            <div class="pl-3 input-group w-100">
                <input id="consultas-encontradas" v-model="totalConsultas" type="text" class="bg-cid w-100 border-0 border-bottom text-white" readonly>
            </div>
        </div>

        <!-- Un div para cada consulta con su contenido -->
        <div v-for="consulta, index in laravelData.data" :key="consulta.id" class="callout cid mb-4 px-0 pb-3" :data-id-consulta="consulta.id">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="align-items-start">
                            <p>{{ consulta.descripcion_area_gestion }}, {{ consulta.fecha_formateada }}</p>
                            <a :href="rutaConsulta(consulta.id)" class="text-nodeco">
                                <strong>{{ consulta.titulo }}</strong>
                            </a>
                            <p v-html="resumen(consulta.consulta, 25)"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paginación al pie -->
        <pagination :data="laravelData" @pagination-change-page="getConsultas"></pagination>

    </div>
</template>

<script>
    Vue.component('pagination', require('laravel-vue-pagination'));

    export default {
        data() {
                return {
                    laravelData: {},
                }
        },
        computed: {
            totalConsultas: function() {
                return (this.laravelData.total == 1) ? this.laravelData.total + " consulta encontrada" : this.laravelData.total + " consultas encontradas";
            },
        },
        props: [
            'urlWebAntigua'
        ],
        methods: {
            resumen: function (texto, mostrar) {
                var resumen = "";
                var cadena = texto.trim();
                var palabras = cadena.split(" ");

                if (palabras.length<mostrar) {
                    var mostrar = palabras.length;
                }
                for (var i = 0; i < mostrar; i++) {
                resumen += " " + palabras[i];
                }
                return resumen + "...";
            },
            getConsultas(page = 1) {
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando proyectos...
                $('#consultas-encontradas').val('Recuperando datos...');

                var url_get = '/api/consultas?page=' + page + filtros;

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error getconsultas');
                    });
            },
            rutaConsulta(id) {
                return '/consultas/'+id;
            },
        }
    }
</script>
