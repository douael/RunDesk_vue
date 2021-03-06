import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';
import Login from '../views/Login';
import Materials from '../views/Materials';
import Dashboard from '../views/Dashboard';
import Categorys from '../views/Categorys';
import Employees from '../views/Employees';
import Borrowings from '../views/Borrowings';
import Profil from '../views/Profil';
import Type from '../views/Type';


Vue.use(VueRouter);

let router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/login', component: Login },
        { path: '/dashboard', component: Dashboard , meta: { requiresAuth: true }},
        { path: '/categorys', component: Categorys, meta: { requiresAuth: true } },
        { path: '/employees', component: Employees, meta: { requiresAuth: true } },
        { path: '/materials', component: Materials, meta: { requiresAuth: true } },
        { path: '/borrowings', component: Borrowings, meta: { requiresAuth: true } },
        { path: '/profil', component: Profil, meta: { requiresAuth: true } },
        { path: '/types', component: Type, meta: { requiresAuth: true } },
        { path: '*', redirect: '/dashboard' , meta: { requiresAuth: true }},
        { path: '*', redirect: '/login' }
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