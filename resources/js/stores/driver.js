import { defineStore } from 'pinia';
import api from '@/axios';

export const useDriverStore = defineStore('driver', {
  state: () => ({
    profile: null,
  }),

  actions: {
    async fetchProfile() {
      const res = await api.get('/drivers/me');
      this.profile = res.data;
    },

    async updateProfile(data) {
      const res = await api.put('/drivers/me', data);
      this.profile = res.data.user;
    },
  },
});
