require('./bootstrap');

window.Vue = require('vue');

require('./components/subscribe-button');
// Vue.component('component1', () => import('./components/Component1.vue'));

const app = new Vue({
    el: '#app'
});
