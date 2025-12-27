import { createRouter, createWebHistory } from 'vue-router'

import { routes } from 'vue-router/auto-routes'
import { useUserStore } from '@/stores/useUserStore'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior() {
    return { top: 0, behavior: 'auto' }
  }
})

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore()

  if (!userStore.user) {
    userStore.initUserFromCache() // không async, dùng ngay
  }

  const isAdminRoute = to.path.startsWith('/admin')
  const isLoggedIn = userStore.isLoggedIn
  const role = userStore.user?.role

  if (isAdminRoute && (!isLoggedIn || role !== 'admin')) {
    return next('/') // không có quyền
  }

  next()
})

export default router
