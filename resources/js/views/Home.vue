<template>
    <div>
      
      <!-- <h2>Home</h2>{{ auth }}
      <div v-if="auth.user?.roles?.some(r => r.name === 'admin')">
    <router-link to="/admin">Admin Dashboard</router-link>
   
  </div> -->
  <router-link to="/roles-manager">Roles Manager</router-link>
  <router-link to="/permission-manager">Permission Manager</router-link>
  <router-link to="/roles-permission-manager">Roles Permission Manager</router-link>
    </div>
  </template>
  
  <script setup>
  import { onMounted ,onBeforeUnmount} from 'vue'
  import { initEcho } from '@/utils/echo';
  import { useAuthStore } from '@/stores/auth';
  
  
  const authStore = useAuthStore();
  const Echo = initEcho(authStore.token);
  const vehicleId=1;
onMounted(() => {
  Echo.private(`vehicle.${vehicleId}`)
  .listen('.TrackingLive', (e) => {
    console.log('✅ TrackingLive Event:', e);
  });
});
onBeforeUnmount(() => {
  Echo.leave(`vehicle.${vehicleId}`);
});
  </script>
  
  