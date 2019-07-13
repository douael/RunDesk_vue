import Vue from 'vue';
import Vuex from 'vuex';
import SecurityModule from './security';
import MaterialModule from './material';
import CategoryModule from './category';
import EmployeeModule from './employee';
import DashboardModule from './dashboard';
import BorrowingModule from './borrowing';
import TypeModule from './type';
import BootstrapVue from 'bootstrap-vue'
import VueSelect from 'vue-select2';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(Vuex);
Vue.use(BootstrapVue);

export default new Vuex.Store({
    modules: {
        security: SecurityModule,
        material: MaterialModule,
        category: CategoryModule,
        borrowing: BorrowingModule,
        employee: EmployeeModule,
        dashboard: DashboardModule,
        type: TypeModule,
    },
});