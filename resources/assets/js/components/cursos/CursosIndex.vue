<template>
    <div>
        <div class="container px-0" id="listaCursos">
            <!-- Cabecera: total de cursos -->
            <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
            <div class="row py-3 mb-3 align-items-center bg-formacion">
                <div class="pl-3 input-group w-100">
                    <input id="cursos-encontrados" v-model="totalCursos" type="text"
                        class="bg-formacion w-100 border-0 border-bottom text-white" readonly>
                </div>
            </div>

            <!-- Un div para cada curso con su contenido -->
            <div v-for="curso, index in laravelData.data" :key="curso.id" class="curso-catologo pointer mb-4 pt-3 pb-3"
                :data-id-curso="curso.id">
                <a :href="rutaCurso(curso)" class="text-nodeco" target="_blank">
                    <div class="container">
                        <div class="row">
                            <div class="col-4 callout-right formacion-secundario">
                                <div v-if="curso.acreditado"
                                    class="bg-success text-center ml-0 w-100 px-3 pt-1 text-white d-table">Acreditado</div>
                                <img v-if="curso.imagen" class="img-fluid flex-auto" :src="curso.ruta_imagen">
                            </div>
                            <div class="col-8">
                                <div class="align-items-start">
                                    <b>{{ curso.titulo }}</b>

                                    <p v-if="curso.formato == 'curso'">Fecha de inicio: {{ curso.fecha_formateada }}</p>
                                    <p v-else>Fecha de creación: {{ curso.fecha_creacion_formateada }}</p>

                                    <p v-if="curso.periodo && curso.formato == 'curso'">Duración: {{ curso.periodo }} días
                                        desde el inicio del curso.</p>
                                    <!-- <p v-if="curso.tutores.length > 0">Tutorías: <span v-for="tutor, index in curso.tutores"><span v-if="index>0">, </span>{{ tutor.nombre_completo }}</span></p> -->

                                    <p v-if="curso.configuracion_socios"><b>Exclusivo para socios</b></p>

                                    <p><b>{{ curso.descripcion_estado }}</b></p>
                                    <span v-if="curso.descripcion !== null">
                                        <p
                                            v-html="$options.filters.nl2br($options.filters.truncate(curso.descripcion, 250))">
                                        </p>
                                    </span>
                                    <p v-if="curso.descripcion_contenido" class="font-italic">{{ curso.descripcion_contenido
                                        }}</p>
                                    <!-- <template v-if="curso.precio_socio == 0 && curso.precio_nosocio == 0 && curso.formato == 'curso' && curso.descripcion_estado != 'Exclusivo para socios'">
                                    <p><strong>Webcast Gratis</strong></p>
                                </template> -->
                                    <template v-else>
                                        <p v-if="curso.creditos > 0 && curso.fecha_fin >= fechaTope">Acreditación:
                                            <strong>{{ curso.creditos }} créditos</strong></p>
                                    </template>
                                    <!-- <template v-if="curso.logo_patrocinador != ''">
                                    <img :src="rutaImagen('logo_p', curso.id, curso.logo_patrocinador)" :alt="curso.patrocinador" :title="curso.patrocinador" height="35" align="absmiddle" />
                                </template>
                                <div v-if="curso.patrocinadores.length > 0">
                                    <template v-if="curso.patrocinio != ''">
                                        <div><strong>{{ curso.patrocinio }}</strong></div>
                                    </template>
                                    <template v-else>
                                        <div><strong>Contacte con</strong></div>
                                    </template>
                                    <template v-for="patro, index in curso.patrocinadores">
                                        <div class="d-inline mx-3">
                                            <a v-if="patro.web != ''" :href="patro.web" target='_blank'>
                                                <img :src="rutaImagen('elearning__p', 0, patro.logotipo)" :alt="patro.nombre" :title="patro.nombre" align="absmiddle" />
                                            </a>
                                            <img v-if="patro.web == ''" :src="rutaImagen('elearning__p', 0, patro.logotipo)" :alt="patro.nombre" :title="patro.nombre" align="absmiddle" />
                                        </div>
                                    </template>
                                </div>
                                <div v-else-if="curso.patrocinio != ''">
                                    <strong>{{ curso.patrocinio }}</strong>
                                </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Paginación al pie -->
            <pagination :data="laravelData" @pagination-change-page="getCursos"></pagination>
        </div>
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
        totalCursos: function () {
            return (this.laravelData.total == 1) ? this.laravelData.total + " curso encontrado" : this.laravelData.total + " cursos encontrados";
            //  return this.laravelData.total + " cursos encontrados";
        },
        // ayer a las 23:59:59
        fechaTope: function () {
            var fecha = new Date();
            fecha.setDate(fecha.getDate() - 1);
            fecha.setHours(23, 59, 59, 999);

            return fecha;
        }
    },
    props: [
        'urlWebAntigua',
        'token',
    ],
    methods: {
        getCursos(page = 1) {
            var instancia = this;
            // es necesario recuperar filtros activos
            var filtros = $('#filtrosGet').val();
            // y guardar la página por si hay un back();
            $('#paginaGet').val(page);
            // mensaje de recuperando cursos...
            $('#cursos-encontrados').val('Recuperando datos...');

            var url_get = '/api/cursos?page=' + page + filtros;

            axios.get(url_get)
                .then(response => {
                    this.laravelData = response.data;
                })
                .catch(function (resp) {
                    console.log('Error getcursos');
                });
        },
        rutaCurso(curso) {
            // if (curso.external)
                // return curso.external_url + this.token;

            return '/cursos/' + curso.id;
        },
        // ruta al fichero de logo (según si viene de logo_patrocinador o de elearning__patrocinador
        rutaImagen: function (tipo, id, imagen) {
            switch (tipo) {
                case "imagen":
                    return this.urlWebAntigua + '/formacion/cursos/img/' + imagen;
                    break;
                case "logo_p":
                    return this.urlWebAntigua + '/formacion/archivos/' + id + '/' + imagen;
                    break;
                case "elearning__p":
                    return this.urlWebAntigua + '/formacion/archivos/patrocinadores/' + imagen;
                    break;
            }

            return '';
        },
        // comprobar si el fichero de la imagen del patrocinador existe o no en el servidor
        existeLogoPatrocinador: function (tipo, id, imagen) {

            var http = new XMLHttpRequest();
            var url = this.rutaImagen(tipo, id, imagen);
            http.open('HEAD', url, false);
            http.send();

            return http.status != 404;
        }
    }
}
</script>
