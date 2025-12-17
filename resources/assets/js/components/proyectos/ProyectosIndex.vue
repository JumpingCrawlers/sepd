<template>
    <div class="container px-0" id="listaProyectos">

        <!-- Cabecera: total de proyectos -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div class="row py-3 mb-3 align-items-center bg-cid">
            <div class="pl-3 input-group w-100">
                <input id="proyectos-encontrados" v-model="totalProyectos" type="text" class="bg-cid w-100 border-0 border-bottom text-white" readonly>
            </div>
        </div>

        <!-- Un div para cada proyecto con su contenido -->
        <div v-for="proyecto, index in laravelData.data" :key="proyecto.id_proyecto" class="mb-4 px-0 pb-3" :data-id-proyecto="proyecto.id_proyecto">
            <div class="container px-0">
                <div class="row">
                    <div class="col-5 col-md-3 pl-0 callout-right cid-secundario">
                        <img v-if="proyecto.miniatura" class="img-fluid flex-auto" :src="proyecto.url_miniatura">
                    </div>
                    <div class="col-7 col-md-9 pl-0">
                        <div :class="proyecto.datos_fase['clase_css'] + ' ml-0 px-3 pt-1 text-white d-table'">
                            Fase: {{ proyecto.datos_fase['descripcion'] }}
                        </div>
                        <div class="align-items-start pl-3 mt-3">
                            <a :href="rutaProyecto(proyecto.id_proyecto)" class="text-nodeco">
                                <strong>{{ proyecto.titulo }}</strong>
                            </a>
                            <template v-if="proyecto.resumen != ''">
                                <p v-html="proyecto.resumen"></p>
                            </template>
                            <template v-else>
                                <p v-html="proyecto.descripcion"></p>
                            </template>
                            <div v-if="proyecto.patrocinadores.length > 0">
                                <hr class="cid">
                                <template v-for="patro, index in proyecto.patrocinadores">
                                    <div class="d-inline mx-3">
                                        <a v-if="patro.web != ''" :href="patro.web" target='_blank'>
                                            <img :src="rutaImagen(patro.logotipo)" :alt="patro.nombre" :title="patro.nombre" align="absmiddle" />
                                        </a>
                                        <img v-if="patro.web == ''" :src="rutaImagen(patro.logotipo)" :alt="patro.nombre" :title="patro.nombre" align="absmiddle" />
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paginación al pie -->
        <pagination :data="laravelData" @pagination-change-page="getProyectos"></pagination>

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
            totalProyectos: function() {
                return (this.laravelData.total == 1) ? this.laravelData.total + " proyecto encontrado" : this.laravelData.total + " proyectos encontrados";
            },
        },
        props: [
            'urlWebAntigua'
        ],
        methods: {
            getProyectos(page = 1) {
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando proyectos...
                $('#proyectos-encontrados').val('Recuperando datos...');

                var url_get = '/api/proyectos?page=' + page + filtros;

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error getproyectos');
                    });
            },
            rutaProyecto(id) {
                return '/proyectos/'+id;
            },
            // ruta al fichero del logo patrocinador
            rutaImagen: function(imagen) {

                return this.urlWebAntigua+'/formacion/archivos/patrocinadores/' + imagen;

            }
        }
    }
</script>
