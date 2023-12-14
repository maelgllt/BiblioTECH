import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: () => import('../components/Home.vue')
        },
        {
            path: '/a-propos',
            component: () => import('../components/About.vue')
        },
        {
            path: '/compte',
            component: () => import('../components/Account.vue')
        }
    ]
})

export default router