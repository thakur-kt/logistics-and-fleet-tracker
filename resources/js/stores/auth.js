import { defineStore } from 'pinia'
import api from '@/axios'
import router from '@/router' 

// Define a Pinia store for authentication and authorization
export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,         // Authenticated user object
    token: null,        // JWT or API token
    errors: null,       // Validation or API errors
    roles: [],          // Array of user roles
    permissions: [],    // Array of user permissions
  }),
  persist: true,        // Enable persistent state (e.g., localStorage)
  actions: {
    // Example API call to test authentication (not used in UI)
    async fetching(){
      api.get('/ping', {
        headers: {
          Authorization: `Bearer ${this.token}`,
        }
      });
    },

    // Register a new user and store credentials/roles/permissions
    async register(form) {
      try {
        const response = await api.post('register', form)
        this.token = response.data.token
        this.user = response.data.user
        this.roles = response.data.roles
        this.permissions = response.data.permissions
        this.errors = null
        // Redirect to home page after successful registration
        router.push({ name: 'home' })
      } catch (error) {
        // Store validation errors if registration fails
        if (error.response && error.response.data) {
          this.errors = error.response.data.errors
        }
      }
    },

    // Login a user and store credentials/roles/permissions
    async login(form) {
      try {
        const res = await api.post('/login', form)
        this.token = res.data.token
        this.user = res.data.user
        this.roles = res.data.roles
        this.permissions = res.data.permissions
        // Redirect to home page after successful login
        router.push({ name: 'home' })
      } catch (err) {
        // Store errors if login fails
        this.errors = err.response?.data?.errors || { general: 'Login failed' }
      }
    },

    // Fetch the authenticated user details
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

    // Logout the user and clear stored credentials
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

    // Check if the user has a specific role
    hasRole(role) {
      return this.roles.includes(role)
    },

    // Check if the user has a specific permission
    can(permission) {
      return this.permissions.includes(permission)
    }
  },
  persist: true,
})
