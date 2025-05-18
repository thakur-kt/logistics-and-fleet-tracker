<template>
    <div>
      <h2>Admin Dashboard</h2>
      <button v-if="auth.can('manage vehicles')">Add Vehicle</button>
      <Vehicles />
      <!-- <Deliveries /> -->
    </div>
  </template>
  
  <script setup>
  import Vehicles from '../components/admin/vehicles/Index.vue';
  import { useAuthStore } from '@/stores/auth'
  const auth = useAuthStore()
  </script>
  