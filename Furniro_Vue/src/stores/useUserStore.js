// stores/useUserStore.ts (nên chuyển sang TypeScript nếu có thể)
import { defineStore } from 'pinia'
import api from '@/api'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    isFetched: false,
  }),

  getters: {
    isLoggedIn: (state) => !!state.user,
    isAdmin: (state) => state.user?.role === 'admin',
    hasPermission: (state) => (permission) => state.user?.permissions?.includes(permission),
  },

  actions: {
    setUser(user) {
      this.user = user
      localStorage.setItem('user', JSON.stringify(user)) // 🧠 lưu vào localStorage
    },

    clearUser() {
      this.user = null
      this.isFetched = false
      localStorage.removeItem('user')
    },

    initUserFromCache() {
      const cached = localStorage.getItem('user')
      if (cached) {
        try {
          this.user = JSON.parse(cached)
        } catch (err) {
          this.user = null
        }
      }
      this.isFetched = true  // ✅ cần có để đảm bảo trạng thái
    },

    async fetchUser() {
      try {
        const res = await api.get('/api/user')
        this.user = res.data
        localStorage.setItem('user', JSON.stringify(res.data))
      } catch {
        this.clearUser()
      } finally {
        this.isFetched = true
      }
    },

    async login(payload) {
      await api.get('/sanctum/csrf-cookie') // nếu dùng Laravel Sanctum
      await api.post('/login', payload)
      await this.fetchUser()
    },

    async logout() {
      await api.post('/logout')
      this.clearUser()
    },
  },
})
