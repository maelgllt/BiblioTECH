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
        },
        {
            path: '/ajout-doc',
            component: () => import('../components/Add_doc.vue')
        }
    ]
})

export default router