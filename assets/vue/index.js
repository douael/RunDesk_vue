import Vue from 'vue';
import App from './App';
import router from './router';
import store from './store';
import VeeValidate from "vee-validate";
import VueFlashMessage from 'vue-flash-message';
new Vue({
    template: '<App/>',
    components: { App },
    router,
    store,
}).$mount('#app');