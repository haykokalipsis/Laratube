require('./bootstrap');

window.Vue = require('vue').default;

// TODO This player realization on vue is buggy, need this line to ignore video-js component. Find another way.
Vue.config.ignoredElements = ['video-js'];

Vue.component('subscribe-button', require('./components/subscribe-button.vue').default);
Vue.component('votes', require('./components/votes.vue').default);
Vue.component('comments', require('./components/comments.vue').default);
require('./components/channel-uploads');
// Vue.component('component1', () => import('./components/Component1.vue '));

const app = new Vue({
    el: '#app'
});
