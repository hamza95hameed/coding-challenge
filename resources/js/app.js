require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'

import axios from 'axios'
import routes from './routes'

Vue.use(VueRouter)

const router = new VueRouter(routes)

function loggedIn() {
    return localStorage.getItem('token')
}

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!loggedIn()) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        if (loggedIn()) {
            next({
                path: '/',
                query: { redirect: to.fullPath }
            })
        } else {
            next()
        }
    } else {
        next()
    }
})

Vue.component('nav-bar', require('./components/Navbar').default);

new Vue({
    el: '#app',
    router,
})