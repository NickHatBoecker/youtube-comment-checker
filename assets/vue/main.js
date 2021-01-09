import Vue from 'vue';
import App from './App';
import router from './router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import store from './store'

import '../css/app.css';

Vue.use(VueAxios, axios)

new Vue({
    el: '#app',
    render: h => h(App),
    router,
    store,
});
