import Index from './components/Index.vue'
import Login from './components/Login.vue'
import Register from './components/Register.vue'
export default {
    mode: 'history',
    fallback: true,
    routes: [
        {
            path: '/',
            component: Index,
            name: 'Index',
            meta: {requiresAuth: true}
        },
        {
            path: '/login',
            component: Login,
            name: 'Login',
            meta: {guest: true}
        },
        {
            path: '/register',
            component: Register,
            name: 'Register',
            meta: {guest: true}
        }
    ]
}