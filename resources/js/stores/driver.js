import { defineStore } from 'pinia';
import api from '@/axios';

// Define a Pinia store for driver-related state and actions
export const useDriverStore = defineStore('driver', {
  // State holds the driver's profile information
  state: () => ({
    profile: null,
  }),

  actions: {
    // Fetch the authenticated driver's profile from the API
    async fetchProfile() {
      const res = await api.get('/drivers/me');
      this.profile = res.data; // Store the profile data in state
    },

    // Update the authenticated driver's profile via the API
    async updateProfile(data) {
      const res = await api.put('/drivers/me', data);
      this.profile = res.data.user; // Update state with the new profile data
    },
  },
});
