import {createRouter, createWebHistory} from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL), routes: [
        {
            path: '/',
            name: 'Home',
            component: HomeView,
            meta: {public: false}
        },
        {
            path: '/login',
            name: 'Login',
            component: () => import('../views/auth/Login.vue'),
            meta: {public: true}
        },
        {
            path: '/signup',
            name: 'Register',
            component: () => import('../views/auth/Register.vue'),
            meta: {public: true}
        },
        {
            path: '/logout',
            name: 'Logout',
            component: () => import('../components/logout.vue'),
            meta: {public: false}
        }, //Tradeings
        {
            path: '/trade',
            name: 'tradeHome',
            component: () => import('../views/trade/home.vue'),
            meta: {public: false}
        }, //Gamblings
        {
            path: '/games',
            name: 'games',
            component: () => import('../views/games/gameHome.vue'),
            meta: {public: false}
        },


        {
            path: '/:pathMatch(.*)*', name: 'Error', component: () => import('../views/Error.vue'), meta: {public: true}
        }

    ]
})
router.beforeEach((to, from, next) => {
    const isPublic = to.matched.some(record => record.meta.public)
    const isLoggedIn = localStorage.getItem('log') == undefined ? 'false' : localStorage.getItem('log');
    if (!isPublic && isLoggedIn == 'false') {
        return next({path: "/login"});
    }


    next();
});
export default router
