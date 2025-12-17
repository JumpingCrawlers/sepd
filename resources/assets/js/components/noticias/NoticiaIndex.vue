<template>
    <div id="listaNoticias">

        <!-- Cabecera: total de las noticias -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div :class="'row py-3 mb-2 align-items-center bg-' + seccion">
            <div class="pl-3 input-group w-100">
                <input id="noticias-encontradas" v-model="totalNoticia" type="text" :class="'bg-' + seccion + ' w-100 border-0 border-bottom text-white'" readonly>
            </div>
        </div>

        <!-- Un div para cada noticia con su contenido -->
        <div v-for="noticias, index in laravelData.data" :key="noticias.id" class="noticias-index px-0" :data-id-noticas="noticias.id">
            <div class="row mb-3">
                <div :class="'col-12 callout ' + seccion + ' flex-row w-100 text-justify'">
                       <p>
                            <strong>{{ noticias.fecha_formateada }}</strong>
                            <br>
                            <em v-html="noticias.titulo"></em> <!-- Para que se apliquen los caracteres especiales de UTF-8 es necesario usar "v-html" -->
                        </p>
                        <p v-html="$options.filters.nl2br($options.filters.truncate(noticias.texto, 290))"></p>
                        <div class="container">
                            <div class="row">
                                <div class="col-6 text-left flex-column w-100 p-0">
                                    <!-- Link  -->
                                    <a v-if="noticias.enlace !== '' && noticias.enlace !== 'http://'" target='_blank' :href="rutaEnlace(noticias.enlace)" class="text-nodeco">
                                        <img :src="rutaIcono('noticias.enlace')" class="img-fluid" width="20px"> Ver enlace
                                    </a>
                                </div>
                                <div class="col-6 float-left text-right flex-column w-100 p-0">
                                    <!-- Link  -->
                                    <a :href="rutaNoticia(noticias.id)" class="text-nodeco">
                                        Ver más
                                    </a>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <!-- Paginación al pie -->
        <pagination :limit="4" :data="laravelData" @pagination-change-page="getNoticia" ></pagination>

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
            totalNoticia: function() {
                return (this.laravelData.total == 1) ? this.laravelData.total + " artículo encontrado" : this.laravelData.total + " artículos encontrados";
            }
        },
        props: [
            'seccion',
            'urlWebAntigua',
            'iconos'
        ],
        methods: {
            getNoticia(page = 1) {
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando cursos...
                $('#noticias-encontrados').val('Recuperando datos...');

                var url_get = 'api/noticias?page=' + page + filtros;

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error recuperando artículos');
                    });
            },
            // icono para el link correspondiente de las noticias, según la extensión del fichero
            rutaIcono: function(enlace) {
                // recuperar la extension del fichero
                var extension = enlace.substring(enlace.lastIndexOf(".")+1);
                var tipoIcono = '';
                switch(extension) {
                    case "jpg":
                    case "jpeg":
                    case "png":
                        tipoIcono = 'imagen';
                        break;
                    case "pdf":
                        tipoIcono = 'pdf';
                        break;
                    case "enlace":
                        tipoIcono = 'enlace';
                        break;
                    case "null":
                        tipoIcono = '';
                        break;
                }

                // recorrer el array de iconos buscando el correspondiente
                var arrayIconos = JSON.parse(this.iconos);
                for (var i = 0; i < arrayIconos.length; i++) {
                    if ( arrayIconos[i].key.indexOf(tipoIcono)>=0 ) {
                        return arrayIconos[i].src;
                    }
                }

                return '';
            },
            // ruta al enlace de la base de datos
            rutaEnlace: function(enlace) {
                return enlace;
            },
            // ruta a la noticia del botón "Ver más" se muestra con el blade "show" de noticias
            rutaNoticia: function(enlace) {
                return '/noticias/' + enlace;
            }
        }
    }
</script>
