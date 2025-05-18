<template>
    <div class="p-4">
      <h2 class="text-xl font-bold mb-4">Vehicles 
        <router-link to="/vehicles/create" class="btn">Add Vehicle</router-link></h2>
  
      <!-- Filter Dropdown -->
      <div class="mb-4">
        <label class="font-semibold mr-2">Filter by Status:</label>
        <select v-model="selectedStatus" @change="filterVehicles" class="border rounded p-1">
          <option value="">All</option>
          <option value="idle">Idle</option>
          <option value="en_route">En Route</option>
          <option value="maintenance">Maintenance</option>
        </select>
      </div>
  
      <!-- Vehicle List -->
      <table class="min-w-full border">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="p-2 border">#</th>
            <th class="p-2 border">Plate</th>
            <th class="p-2 border">Model</th>
            <th class="p-2 border">Status</th>
            <th class="p-2 border">Driver</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(vehicle, index) in filteredVehicles" :key="vehicle.id">
            <td class="p-2 border">{{ index + 1 }}</td>
            <td class="p-2 border">{{ vehicle.number_plate }}</td>
            <td class="p-2 border">{{ vehicle.model }}</td>
            <td class="p-2 border">{{ vehicle.status }}</td>
            <td class="p-2 border">{{ vehicle.user?.name ?? 'Unassigned' }}</td>
            <td><router-link :to="`/vehicles/${vehicle.id}/edit`" class="btn">Edit</router-link></td>
          </tr>
          <tr v-if="filteredVehicles.length === 0">
            <td colspan="5" class="p-2 text-center text-gray-500">No vehicles found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue'
  import axios from 'axios'
  
  const vehicles = ref([])
  const selectedStatus = ref('')
  
  const fetchVehicles = async () => {
    try {
      const res = await axios.get('/api/vehicles')
      vehicles.value = res.data
    } catch (err) {
      console.error('Error fetching vehicles:', err)
    }
  }
  
  const filteredVehicles = computed(() => {
    if (!selectedStatus.value) return vehicles.value
    return vehicles.value.filter(v => v.status === selectedStatus.value)
  })
  
  const filterVehicles = () => {
    // Computed handles filtering
  }
  
  onMounted(() => {
    fetchVehicles()
  })
  </script>
  