import './bootstrap';

import { createApp } from 'vue';
import { createPinia } from 'pinia'
import App from './components/App.vue';
import router from './router';
import '../css/app.css'; // Import Tailwind
import api from './axios'
import { useAuthStore } from '@/stores/auth'

import piniaPersistedstate from 'pinia-plugin-persistedstate'
const pinia = createPinia()
pinia.use(piniaPersistedstate)
createApp(App)
  .use(router)
  .use(pinia)
  .mount('#app');
  

api.interceptors.request.use((config) => {
  const auth = useAuthStore()
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`
  }
  return config
}, error => Promise.reject(error))