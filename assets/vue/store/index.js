import Vue from 'vue';
import Vuex from 'vuex';
import SecurityModule from './security';
import MaterialModule from './material';
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(Vuex);
Vue.use(BootstrapVue);

export default new Vuex.Store({
    modules: {
        security: SecurityModule,
        material: MaterialModule,
    },
});