let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/biblioteca.js', 'public/js')
   .js('resources/assets/js/calendario.js', 'public/js')
   .js('resources/assets/js/calendarioListado.js', 'public/js')
   .js('resources/assets/js/consultas.js', 'public/js')
   .js('resources/assets/js/cursos.js', 'public/js')
   .js('resources/assets/js/dossier.js', 'public/js')
   .js('resources/assets/js/empleos.js', 'public/js')
   .js('resources/assets/js/filtros.js', 'public/js')
   .js('resources/assets/js/galeria.js', 'public/js')
   .js('resources/assets/js/imagen.js', 'public/js')
   .js('resources/assets/js/noticias.js', 'public/js')
   .js('resources/assets/js/prensa.js', 'public/js')
   .js('resources/assets/js/proyectos.js', 'public/js')
   .js('resources/assets/js/revistas.js', 'public/js')
   .js('resources/assets/js/seleccionaOpcion.js', 'public/js')
   .js('resources/assets/js/sepdtv.js', 'public/js')
   .js('resources/assets/js/video.js', 'public/js')
   .js('resources/assets/js/vue.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/calendario.scss', 'public/css')
   .sass('resources/assets/sass/dossier.scss', 'public/css')
   .sass('resources/assets/sass/galeria.scss', 'public/css')
   .sass('resources/assets/sass/proyectos.scss', 'public/css')
   .sass('resources/assets/sass/sepd.scss', 'public/css')
   .sass('resources/assets/sass/sepdtv.scss', 'public/css');
