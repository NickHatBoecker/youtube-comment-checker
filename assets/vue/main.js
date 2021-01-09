import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import App from './App';
import router from './router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import store from './store'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueAxios, axios)

new Vue({
    el: '#app',
    render: h => h(App),
    router,
    store,
    beforeCreate () {
        this.$store.dispatch('initialiseStore')
    },
});
