import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'

// Lazy-loaded routes to keep initial bundle small
const Home = () => import('../components/HelloWorld.vue')
const Sales = () => import('../views/Sales.vue')

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
    component: Sales,
    meta: { title: 'Sales' }
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

// Optional: update document title per route
router.afterEach((to) => {
  const base = 'Sales App'
  const title = (to.meta && (to.meta as any).title) || undefined
  document.title = title ? `${String(title)} — ${base}` : base
})

export default router
