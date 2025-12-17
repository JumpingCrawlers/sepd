/**
 * Las páginas con refresco de contenido (filtros de Cursos/Bibliotecas) 
 * utilizan vue. La instancia con sus métodos concretos se crea en el js de cada página
 */

window.Vue = require('vue');

Vue.filter('truncate', function (text, stop, clamp) {
    return text.slice(0, stop) + (stop < text.length ? clamp || ' ...' : '')
})

Vue.filter('nl2br', function(str) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + '<br>' + '$2');
})

import lightbox2 from 'lightbox2';
Vue.use(lightbox2);

//Vue.filter('truncate', filter);
//Vue.filter('nl2br', filter);

