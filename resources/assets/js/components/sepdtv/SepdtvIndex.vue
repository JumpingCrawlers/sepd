<template>
    <div id="listaSepdtv">
        <!-- Cabecera: total de sepdtv -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div class="row py-3 mb-3 align-items-center bg-formacion">
            <div class="pl-3 input-group w-100">
                <input id="sepdtv-encontrados" v-model="totalSepdtv" type="text"
                    class="bg-formacion w-100 border-0 border-bottom text-white" readonly>
            </div>
        </div>

        <!-- Un div para cada sepdtv con su contenido -->
        <div v-for="sepdtv, index in laravelData.data" :key="sepdtv.codigo" class="curso-catologo pointer mb-4 pt-3 pb-3"
            :data-id-curso="sepdtv.id">
            <a :href="rutaSepdtv(sepdtv)" class="text-nodeco" target="_blank">
                <div class="container">
                    <div class="row">
                        <div class="col-4 callout-right formacion-secundario">
                            <img :src="rutaPoster(sepdtv.codigo)" class="img-fluid flex-auto">
                        </div>

                        <div class="col-8">
                            <div class="align-items-start">
                                <b>{{ sepdtv.titulo }}</b>    
                                <p>{{ sepdtv.subtitulo }}</p>
                                <p v-html="$options.filters.nl2br(sepdtv.descripcion_sort)"></p>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Paginación al pie -->
        <pagination :data="laravelData" @pagination-change-page="getSepdtv"></pagination>
    </div>
</template>

<script>

    Vue.component('pagination', require('laravel-vue-pagination'));

    export default {
        data() {
            return {
                laravelData: {}
            }
        },
        computed: {
            totalSepdtv: function () {
                return (this.laravelData.total == 1) ? this.laravelData.total + " vídeo encontrado" : this.laravelData.total + " vídeos encontrados";
            }
        },
        props: [
            'url',
            'urlBack',
            'seccion'
        ],
        methods: {
            getSepdtv(page = 1) {
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando sepdtv...
                $('#sepdtv-encontrados').val('Recuperando datos...');

                var url_get = '/api/sepdtv?page=' + page + filtros;

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error getcursos');
                    });
            },
            // ruta a la imagen miniatura
            rutaThumbnail: function(codigo) {

                return `${this.urlBack}/storage/sepd_tv/thumbnail/${codigo}.jpg`;

            },

            rutaSepdtv (sepdtv) {
                return '/sepdtv/video/' + sepdtv.codigo;
            },

            rutaPoster: function(codigo) {

                return `${this.urlBack}/storage/sepd_tv/portada/${codigo}.jpg`;

            },
        },
    }

</script>
