<template>
    <div id="listaRevistas">

        <!-- Cabecera: total publicaciones encontradas -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div :class="'row py-3 mb-2 align-items-center bg-'+seccion">
            <div class="pl-3 input-group w-100">
                <input id="revistas-encontrados" v-model="totalRevistas" type="text" :class="'bg-'+seccion+' w-100 border-0 border-bottom text-white'" readonly>
            </div>
        </div>

        <!-- Un div para cada publicación con su contenido -->
        <div v-for="revistas, index in laravelData.data" :key="revistas.id" class="prensa-index px-0 " :data-id-prensa="revistas.id">
            
            <div v-if="index == 0" class="col-xs-12 col-md-12 p-0">

                <!-- Si es la primera posicion es la última revista insertada y el user está logueado -->
                <div v-if="revistas.archivo !== undefined" :class="'callout '+seccion+' mb-4'">
                    <div v-if="revistas.id_revista==2" class="mb-4"> <!-- Si es el tipo de revista 2 corresponde a las de info-sepd, se ven en el iframe -->
                        <strong>Número {{ revistas.numero }} - {{ revistas.year }}</strong>
                        <p v-html="revistas.descripcion"></p>
                        <iframe allowfullscreen="allowfullscreen" :src="rutaRevistas(revistas.archivo, revistas.tipo_archivo)" type="application/pdf" width="100%" height="250" frameborder="0" ref="frame"></iframe>
                    </div>

                    <!-- Si es el tipo de revista 1 corresponde a las de Hepatology, se descargan -->
                    <div v-if="revistas.id_revista==1" class="row mb-4 ultima-revista"> 
                        <div class="col-md-6 col-xs-12 float-left mb-4">
                            <strong>Número {{ revistas.numero }} - {{ revistas.year }}</strong>
                            <em v-html="revistas.descripcion"></em>
                            <p class="mt-3">
                                <strong>GI&Hepatology News</strong> es una publicación digital con frecuencia trimestral que recoge 
                                una selección de los mejores artículos publicados por la revista de la AGA.
                            </p>
                            <div class="text-center mt-4">
                                <a target="_blank" class="btn text-white" role="button" :style="estiloBoton" :href="rutaRevistas(revistas.archivo, revistas.tipo_archivo)">
                                    Descargar pdf
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 float-left mb-4">
                            <a v-if="revistas.archivo!=undefined" :href="rutaRevistas(revistas.archivo, revistas.tipo_archivo)" target="_blank">
                                <img :src="rutaImagen(revistas.portada)" :alt="'Revista '+revistas.numero" :title="'Revista '+revistas.numero" width="180" height="auto" align="absmiddle" />
                            </a>
                        </div>
                    </div>

                    <!-- Si es el tipo de revista 3 corresponde a las de gastro-news, se descargan -->
                    <div v-if="revistas.id_revista==3" class="row mb-4 ultima-revista ' + seccion + '"> 
                        <div class="col-md-6 col-xs-12 float-left mb-4">
                            <strong>Número {{ revistas.numero }} - {{ revistas.year }}</strong>
                            <em v-html="revistas.descripcion"></em>
                            <p class="mt-3">
                                <strong>SEPD Gastronews</strong> es el newsletter oficial de la SEPD. 
                                Se publica con una periodicidad mensual y presenta la ultima actualidad científica e institucional.
                            </p>
                            <br />
                            <a role="button" class="btn text-white" :style="estiloBoton" :href="'/gastro-news/'+revistas.id">Ver newsletter</a>
                        </div>
                        <div class="col-md-6 col-xs-12 float-left mb-4">
                            <a :href="'/gastro-news/'+revistas.id">
                                <img :src="rutaImagen(revistas.portada)" :alt="'Revista '+revistas.numero" :title="'Revista '+revistas.numero" width="180" height="auto" align="absmiddle" />
                            </a>
                        </div>
                    </div>
                        
                </div>
                <!-- Si es la primera posicion es la última revista insertada y el user NO está logueado -->
                <div v-else>
                    <div v-if="revistas.id_revista==2" class="mb-4 ultima-revista"> 
                        <div class="col-1">
                            <!-- Link  -->
                        </div>
                        <div :class="'col-md-12 col-xs-12 callout ' + seccion + ' float-left mb-4 ultima-revista'">
                            <div class="col-md-6 col-xs-12 float-left mb-4">
                                <strong>Número {{ revistas.numero }} - {{ revistas.year }}</strong>
                                <em v-html="revistas.descripcion"></em>
                                <p class="mt-3">
                                    <strong>Info.SEPD</strong> es el principal soporte de comunicación interna de la SEPD.
                                    Se publica en formato electrónico y con una periodicidad trimestral.
                                    Presenta un contenido eminentemente científico.
                                </p>
                                <div class="text-center mt-4">
                                    <a class="btn" data-toggle="modal" data-target="#modalLogin" :href="'#'">
                                        <em>Contenido exclusivo para socios</em>
                                        <div class="candado d-inline"></div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 float-left mb-0">
                                <a data-toggle="modal" data-target="#modalLogin" :href="'#'">
                                    <img :src="rutaImagen(revistas.portada)" :alt="'Revista '+revistas.numero" :title="'Revista '+revistas.numero" width="180" height="auto" align="absmiddle" />
                                </a>
                            </div> 
                        </div>
                    </div>
                    <!-- 3ways Euro Fuenmayor ajustado condicional para el caso de tipo de revista 4-->
                    <div v-if="revistas.id_revista==1 || revistas.id_revista==4" class="mb-4 ultima-revista">
                        <div class="col-1">
                            <!-- Link  -->
                        </div>
                        <div :class="'col-md-12 col-xs-12 callout ' + seccion + ' float-left mb-4 ultima-revista'">
                            <div class="col-md-6 col-xs-12 float-left mb-4">
                                <strong>Número {{ revistas.numero }} - {{ revistas.year }}</strong>
                                <em v-html="revistas.descripcion"></em>
                                <!-- 3ways Euro Fuenmayor ajustado condicionales para el caso de tipo de revista 1 y 4-->
                                <p v-if="revistas.id_revista==1" class="mt-3">
                                  <strong>GI&Hepatology News</strong>
                                  es una publicación digital con frecuencia trimestral que recoge
                                  una selección de los mejores artículos publicados por la revista de la AGA.
                                </p>
                                <p v-if="revistas.id_revista==4" class="mt-3">
                                  <strong>International Gastroenterology News</strong>
                                  es una publicación digital de carácter trimestral que tiene como objetivo compartir, con los socios de la SEPD, contenido científico relevante sobre la especialidad de Aparato Digestivo. Está compuesta por una selección de los artículos más destacados, de libre acceso, de las siguientes revistas cientificas: UEG Journal, World Journal of Gastroentorology, Gastroenterology, Hepatology & Endoscopy International Open y Revista Española de Enfermedades Digestivas (REED).
                                </p>
                                <div class="text-center mt-4">
                                    <a class="btn" data-toggle="modal" data-target="#modalLogin" :href="'#'">
                                        <em>Contenido exclusivo para socios</em>
                                        <div class="candado d-inline"></div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 float-left mb-0">
                                <a data-toggle="modal" data-target="#modalLogin" :href="'#'">
                                    <img :src="rutaImagen(revistas.portada)" :alt="'Revista '+revistas.numero" :title="'Revista '+revistas.numero" width="180" height="auto" align="absmiddle" />
                                </a>
                            </div> 
                        </div>
                    </div>
                    <div v-else>
                      <!-- 3ways Euro Fuenmayor ajustado condicional para el caso de tipo de revista 4-->
                      <div v-if="revistas.id_revista==3 || revistas.id_revista==4" class="mb-4 ultima-revista">
                            <div v-if="revistas.id_revista==3" class="row mb-4 ultima-revista ' + seccion + '"> 
                                <div class="col-md-6 col-xs-12 float-left mb-4">
                                    <strong>Número {{ revistas.numero }} - {{ revistas.year }}</strong>
                                    <em v-html="revistas.descripcion"></em>
                                    <!-- 3ways Euro Fuenmayor ajustado condicionales para el caso de tipo de revista 1 y 4-->
                                    <p v-if="revistas.id_revista==1" class="mt-3">
                                      <strong>GI&Hepatology News</strong>
                                      es una publicación digital con frecuencia trimestral que recoge
                                      una selección de los mejores artículos publicados por la revista de la AGA.
                                    </p>
                                    <p v-if="revistas.id_revista==4" class="mt-3">
                                      <strong>International Gastroenterology News</strong>
                                      es una publicación digital de carácter trimestral que tiene como objetivo compartir, con los socios de la SEPD, contenido científico relevante sobre la especialidad de Aparato Digestivo. Está compuesta por una selección de los artículos más destacados, de libre acceso, de las siguientes revistas cientificas: UEG Journal, World Journal of Gastroentorology, Gastroenterology, Hepatology & Endoscopy International Open y Revista Española de Enfermedades Digestivas (REED).
                                    </p>
                                    <br />
                                    <a role="button" class="btn text-white" :style="estiloBoton" :href="'/gastro-news/'+revistas.id">Ver newsletter</a>
                                </div>
                                <div class="col-md-6 col-xs-12 float-left mb-4">
                                    <a :href="'/gastro-news/'+revistas.id">
                                        <img :src="rutaImagen(revistas.portada)" :alt="'Revista '+revistas.numero" :title="'Revista '+revistas.numero" width="180" height="auto" align="absmiddle" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             
            </div>

            <div v-else class="col-xs-12">
                <div class="col-1">
                <!-- Link  -->
                </div>
                <div :class="'col-md-6 col-xs-6 callout ' + seccion + ' flex-row float-left mb-4'">
                    <div class="d-flex flex-column">
                        <p>
                            <strong>Número {{ revistas.numero }} - {{ revistas.year }}</strong>
                            <br>
                            <em v-html="revistas.descripcion"></em>
                        </p>
                        <p>
                            <a v-if="revistas.archivo!=undefined" :href="rutaRevistas(revistas.archivo, revistas.tipo_archivo)" target="_blank">
                                <img  :src="rutaImagen(revistas.portada)" :alt="'Revista '+revistas.numero" :title="'Revista '+revistas.archivo" width="115" height="134" align="absmiddle" />
                            </a>
                            <a v-else data-toggle="modal" data-target="#modalLogin" :href="'#'">
                                <img :src="rutaImagen(revistas.portada)" :alt="'Revista '+revistas.numero" :title="'Revista '+revistas.numero" width="115" height="134" align="absmiddle" />
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row w-100">
            <!-- Paginación al pie -->
            <pagination :data="laravelData" @pagination-change-page="getRevistas"></pagination>
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
        computed: {
            totalRevistas: function() {
                return (this.laravelData.total == 1) ? this.laravelData.total + " artículo encontrado" : this.laravelData.total + " números encontrados";
            }
        },
        props: [
            'url',
            'urlBack',
            'iconos',
            'seccion',
            'estiloBoton'
        ],
        methods: {

            getRevistas(page = 1) {
                //3ways Euro Fuenmayor optimizado lógica para detectar tipo de revista segun el url
                let $path = window.location.pathname;
                var tipo=2;
                if ($path === '/gi-hepatology-news') {
                  tipo=1;
                } else if ($path === '/gastro-news') {
                  tipo=3;
                } else if ($path === '/international-gastroenterology-news') {
                  tipo=4;
                }
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando cursos...
                $('#prensa-encontrados').val('Recuperando datos...');
                    
                var url_get = 'api/revistas?page=' + page + filtros+'&tipo='+tipo;

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error recuperando artículos');
                    });
            
            },
            // icono para el link correspondiente de la nota de prensa, según la extensión del fichero
            rutaIcono: function(enlace) {
                // recuperar la extension del fichero
                var extension = enlace.substring(enlace.lastIndexOf(".")+1);
                var tipoIcono = '';loa 
               switch(extension) {
                    case "jpg":
                    case "jpeg":
                    case "png":
                        tipoIcono = 'imagen';
                        break;
                    case "pdf":
                        tipoIcono = 'pdf';
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
            // ruta al fichero de la imagen de la revista
            // hay que controlar si es servidor antiguo o servidor viejo
            // las del servidor nuevo tienen '/' en algún punto, ya que se suben con Voyager
            rutaImagen: function(imagen) {
                var url = '';
                if (imagen.indexOf('/') !== -1) url = '/storage/' + imagen;
                else url = `${this.urlBack}/storage/publicaciones/thumbnail/${imagen}`;

                return url;
            },
            // ruta al documento o video o....
            rutaRevistas: function(enlace, tipo) {
               switch(tipo) {
                    case "pdf":
                        return `${this.urlBack}/storage/publicaciones/archivo/${enlace}`;
                    break;

                    case "url":
                        if (enlace.indexOf("http") == -1) return (this.urlBack + (!enlace.startsWith('/storage') ? ('/storage/' + enlace) : enlace));
                        else return enlace;
                    break;

                    default: return enlace;
                }
            }
        }
    }
</script>
