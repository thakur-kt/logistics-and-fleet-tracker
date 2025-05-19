import { defineStore } from 'pinia'
import api from '@/axios'
import router from '@/router' 
export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    errors: null,
    roles: [],
    permissions: [],
  }),
  persist: true,
  actions: {
    async fetching(){
      api.get('/ping', {
        headers: {
          Authorization: `Bearer ${this.token}`,
        }
      });
    },
    async register(form) {
      
        try {
          const response = await api.post('register', form)
  
          this.token = response.data.token
          this.user = response.data.user
          this.roles = response.data.roles
          this.permissions = response.data.permissions
          this.errors = null
  
          // Store token in localStorage (optional)
          // localStorage.setItem('token', this.token)
  
          // ✅ Redirect to home page
          router.push({ name: 'home' }) // or '/' if you're using path-based routes
  
        } catch (error) {
          if (error.response && error.response.data) {
            this.errors = error.response.data.errors
          }
        }
      },
    async login(form) {
     
      try {
        
        const res = await api.post('/login', form)
        this.token = res.data.token
        this.user = res.data.user
        this.roles = res.data.roles
        this.permissions = res.data.permissions
        router.push({ name: 'home' })
      } catch (err) {
        this.errors = err.response?.data?.errors || { general: 'Login failed' }
      }
    },
    async fetchUser() {
      if (!this.token) return
      try {
        const res = await api.get('/user', {
          headers: { Authorization: `Bearer ${this.token}` }
        })
        this.user = res.data
      } catch {
        this.logout()
      }
    },
    async logout() {
      await api.get('/sanctum/csrf-cookie'); // important
      await api.post('/logout')
      this.user = null
      this.roles = []
      this.permissions = []
      this.token = null
      localStorage.removeItem('pinia')
      router.push({ name: 'login' })
    },
    hasRole(role) {
      return this.roles.includes(role)
    },
    can(permission) {
      return this.permissions.includes(permission)
    }
  },
  persist: true,
})
