import { createRouter, createWebHistory } from 'vue-router';
import LandingPage from '../components/LandingPage.vue';
import Login from '../components/Login.vue';
import Register from '../components/Register.vue';
import Home from '../components/Home.vue';
import CreateBlog from '../components/CreateBlog.vue';
import BlogView from '../components/BlogView.vue';

const routes = [
    { path: '/', component: LandingPage },
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/home', component: Home, meta: { requiresAuth: true } },
    { path: '/create-blog', component: CreateBlog },
    { path: '/blog/:id', component: BlogView },
];

const router = createRouter({
    history: createWebHistory('/'),
    routes,
});

// Guard to check if route requires authentication
router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('auth_token');
    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login');
    } else {
        next();
    }
});

export default router;
