<template>
    <div id="listaCalendario" class="px-0">

        <!-- Cabecera: total de la prensa -->
        <!-- Tiene el mismo estilo que el buscador, ya que la cabecera debe ocupar el mismo espacio -->
        <div id="containerEventosEncontrados" :class="'py-1 align-items-center bg-' + seccion">
            <div class="pl-3 input-group w-100">
                <input id="calendario-encontrados" v-model="totalEventos" type="text" :class="'bg-' + seccion + ' w-100 border-0 border-bottom text-white'" readonly>
            </div>
        </div>
        
        <div class="contenido-listado w-100 mt-3">
            <!-- Un div para cada evento -->
            <div v-for="calendario, index in laravelData.data" :key="calendario.id" class="eventos-index px-0" :data-id-prensa="calendario.id">
                <div class="mb-3">                   
                    <div :class="'callout ' + calendario.seccion + ' flex-row w-100'">
                        <div>
                            <strong>{{ calendario.fecha_formateada }}</strong> - {{ calendario.lugar }}
                            <br>
                            <a class="pointer" v-on:click="getEvento(calendario.id)" :data-id="calendario.id" v-html="'<p>'+calendario.titulo+'</p>'"></a> <!-- Para que se apliquen los caracteres especiales de UTF-8 es necesario usar "v-html" -->
                        </div>
                        <!--<p v-html="calendario.texto"></p>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="ver-evento col-12 d-none px-0 mb-2">
            <div class="row">
                <div class="col-12 text-right">
                    <a v-if="evento !== ''" href="/calendario" v-on:click="goBack()">Lista de eventos</a>
                    <a v-else href="javascript: void(0)" v-on:click="goBack()">Volver a la lista</a>
                </div>
            </div>
            <div class="contenido-evento callout">
                <span class="fecha-evento"></span><br>
                <strong class="titulo-evento"></strong><br>
                <span class="lugar-evento"></span>
                <div class="txt-evento"></div>
                <div class="imagen-evento">
                </div>
                <div class="col-12 text-left link-evento d-none">
                    <a target="_blank" v-html="'Ver enlace'"></a>
                </div>
            </div>
        </div>

        <!-- Paginación al pie -->
        <pagination class="mt-2" :limit="4" :data="laravelData" @pagination-change-page="getEventos"></pagination>

    </div>
</template>

<script>
    Vue.component('pagination', require('laravel-vue-pagination'));
    export default {
        data() {
            return {
                laravelData: {},
                laravelDataEvento: {},
            }
        },
        computed: {
            totalEventos: function() {
                return (this.laravelData.total == 1) ? this.laravelData.total + " evento encontrado" : this.laravelData.total + " eventos encontrados";
            },
            seccionEvento: function() {
                return 
            }
        },
        props: [
            'seccion',
            'evento'
        ],
        methods: {
            getEventos(page = 1) {

                // recuperar eventos -> Próximos eventos + Encontrados
                $('#tituloDetalleEventos').html('Próximos Eventos');
                if ($('#containerEventosEncontrados').hasClass('d-none')) {
                    $('#containerEventosEncontrados').removeClass('d-none');
                }
                
                var instancia = this;
                // es necesario recuperar filtros activos
                var filtros = $('#filtrosGet').val();
                // y guardar la página por si hay un back();
                $('#paginaGet').val(page);
                // mensaje de recuperando cursos...
                $('#calendario-encontrados').val('Recuperando datos...');

                var url_get = '/api/calendario?page=' + page + filtros;

                axios.get(url_get)
                    .then(response => {
                        this.laravelData = response.data;
                    })
                    .catch(function (resp) {
                        console.log('Error recuperando artículos');
                    });

            },
            goBack() {

                $('.ver-evento').addClass('d-none');
                $('.eventos-index').removeClass('d-none');
                // volver a Próximos eventos
                $('#tituloDetalleEventos').html('Próximos Eventos');
                if ($('#containerEventosEncontrados').hasClass('d-none')) {
                    $('#containerEventosEncontrados').removeClass('d-none');
                }

            },
            getEvento(id) {
               
                // Detalle evento -> Detalle evento + Encontrados oculto
                $('#tituloDetalleEventos').html('Detalle evento');
                if (!$('#containerEventosEncontrados').hasClass('d-none')) {
                    $('#containerEventosEncontrados').addClass('d-none');
                }

                var url_get = '/api/calendario?id_evento=' + id;

                axios.get(url_get)
                    .then(response => {
                        $('.ver-evento').removeClass('d-none');
                        $('.eventos-index').addClass('d-none');
                        $('.fecha-evento').html(response.data.data[0].fecha_formateada);
                        $('.lugar-evento').html(response.data.data[0].lugar);
                        $('.titulo-evento').html(response.data.data[0].titulo);
                        $('.txt-evento').html(response.data.data[0].texto);
                        if (response.data.data[0].imagen!=' ') $('.imagen-evento').html('<img src="'+response.data.data[0].ruta_imagen+'">');
                        if (response.data.data[0].enlace!=null){
                            $('.link-evento').removeClass('d-none');
                            $('.link-evento a').attr('href',response.data.data[0].enlace);
                        } else {
                            $('.link-evento').addClass('d-none');
                        }
                        // callout del evento
                        $('.contenido-evento').attr('class', 'contenido-evento callout ' + response.data.data[0].seccion);

                        // mover el calendario al mes correspondiente al evento
                        // recuperar la instancia del full calendar
                        var fc = $('.fc');
                        // y mandarlo a la fecha
                        fc.fullCalendar('gotoDate', response.data.data[0].fecha);
                          
                    })
                    .catch(function (resp) {
                        console.log(resp);
                    });
            }
        }
    }
</script>