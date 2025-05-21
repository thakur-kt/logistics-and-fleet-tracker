<template>
  <div class="p-4 max-w-5xl mx-auto">
    <AdminView/>
   <DriverView />
   <VehicleMap />
  </div>
</template>
  
  <script setup>
import DriverView from '@/views/DriverView.vue';
import AdminView from '@/views/AdminView.vue';
import VehicleMap from '@/components/admin/vehicles/VehicleMap.vue';

  </script>
  
  