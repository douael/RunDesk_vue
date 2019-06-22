import Vue from 'vue';
import Vuex from 'vuex';
import SecurityModule from './security';
import PostModule from './post';
import MaterialModule from './material';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        security: SecurityModule,
        material: MaterialModule,
    },
});