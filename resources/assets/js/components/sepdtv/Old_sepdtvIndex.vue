<template>
    <div id="listaSepdtv">

        <!-- Un div para cada nota de sepdtv con su contenido -->
        <div class="row">
             <div class="col-sm-12 {!! seccion !!} ">
                <div v-for="sepdtv, index in laravelData.data" :key="sepdtv.codigo" class="sepdtv-index px-0 position-relative">
                    <div class="detalles-sepdtv px-3 py-1">
                        <div class="container">
                            <div class="row">
                                <div class="col-2 px-0 flotante">
                                    <img class="img-fluid rounded" :src="rutaThumbnail(sepdtv.codigo)">
                                </div>
                                <div class="col-10 py-1">
                                    <span v-html="$options.filters.nl2br(sepdtv.descripcion)" class="small"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gris-fondo mb-2">
                        <div class="row px-0 py-2 m-0 d-table pointer" :data-titulo="sepdtv.titulo" :data-subtitulo="sepdtv.subtitulo" :data-descripcion="sepdtv.descripcion" :data-contador="sepdtv.contador" :data-codigo="sepdtv.codigo">
                            <div class="col-sm-11 d-table-cell align-middle my-2 bg-gris-fondo">
                                <div :class="'color-'+seccion">
                                    <!--<strong>{{ sepdtv.fecha_formateada }}</strong>
                                    <br>-->
                                    <strong v-html="sepdtv.titulo"></strong> <!-- Para que se apliquen los caracteres especiales de UTF-8 es necesario usar "v-html" -->
                                </div>
                                <em v-html="sepdtv.subtitulo"></em>
                                <!--<p v-html="sepdtv.descripcion"></p>-->
                            </div>
                            <div class="col-sm-1 d-table-cell align-middle">
                                <div :class="'flecha-r '+seccion"></div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Paginación al pie -->
                <pagination :limit="4" :data="laravelData" @pagination-change-page="getSepdtv"></pagination>
            </div>
        </div>
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
                // mensaje de recuperando cursos...
                $('#sepdtv-encontrados').val('Recuperando datos...');
                var area = window.location.pathname.split('/')[2];
                if (area != undefined && area != 'video') {
                    area = "&area_tv="+area;
                    //console.log(area);
                    var url_get = '/api/sepdtv?page=' + page + area + filtros;
                }else{
                    var url_get = '/api/sepdtv?page=' + page + filtros;
                }

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error recuperando artículos');
                    });
            },
            // ruta a la imagen miniatura
            rutaThumbnail: function(codigo) {

                return `${this.urlBack}/storage/sepd_tv/thumbnail/${codigo}.jpg`;

            }

        }

    }

</script>
