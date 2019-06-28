import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';
import Home from '../views/Home';
import Login from '../views/Login';
import Materials from '../views/Materials';
import Dashboard from '../views/Dashboard';
import Categorys from '../views/Categorys';

Vue.use(VueRouter);

let router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/home', component: Home },
        { path: '/login', component: Login },
        { path: '/dashboard', component: Dashboard },
        { path: '/categorys', component: Categorys, meta: { requiresAuth: true } },
        { path: '/materials', component: Materials, meta: { requiresAuth: true } },
        { path: '*', redirect: '/home' }
    ],
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (store.getters['security/isAuthenticated']) {
            next();
        } else {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });
        }
    } else {
        next(); // make sure to always call next()!
    }
});

export default router;