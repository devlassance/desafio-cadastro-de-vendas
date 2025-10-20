import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'

const Home = () => import('../components/HelloWorld.vue')
const Sale = () => import('../views/Sale.vue')
const Seller = () => import('../views/Seller.vue')

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { title: 'Home' }
  },
  {
    path: '/sales',
    name: 'sales',
    component: Sale,
    meta: { title: 'Sales' }
  },

  {
    path: '/seller',
    name: 'seller',
    component: Seller,
    meta: { title: 'Seller' }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, _from, savedPosition) {
    if (savedPosition) return savedPosition
    if (to.hash) return { el: to.hash, behavior: 'smooth' }
    return { top: 0 }
  }
})

router.afterEach((to) => {
  const base = 'Sales App'
  const title = (to.meta && (to.meta as any).title) || undefined
  document.title = title ? `${String(title)} — ${base}` : base
})

export default router
