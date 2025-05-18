<!-- resources/js/pages/Vehicles.vue -->
<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-xl font-bold">Vehicles</h1>
      <button @click="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Vehicle</button>
    </div>

    <table class="min-w-full bg-white shadow rounded">
      <thead class="bg-gray-100 text-left text-sm">
        <tr>
          <th class="p-2">#</th>
          <th class="p-2">Number Plate</th>
          <th class="p-2">Model</th>
          <th class="p-2">Status</th>
          <th class="p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(vehicle, index) in vehicles" :key="vehicle.id" class="border-t text-sm">
          <td class="p-2">{{ index + 1 }}</td>
          <td class="p-2">{{ vehicle.number_plate }}</td>
          <td class="p-2">{{ vehicle.model }}</td>
          <td class="p-2">{{ vehicle.status }}</td>
          <td class="p-2 space-x-2">
            <button @click="openModal(vehicle)" class="text-blue-600 text-xs">Edit</button>
            <button @click="deleteVehicle(vehicle.id)" class="text-red-600 text-xs">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <VehicleForm
      :vehicle="selectedVehicle"
      :show="showModal"
      @close="showModal = false"
      @saved="fetchVehicles"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/axios'
import VehicleForm from './Form.vue'

const vehicles = ref([])
const showModal = ref(false)
const selectedVehicle = ref(null)

const fetchVehicles = async () => {
  const res = await api.get('/vehicles')
  vehicles.value = res.data
}

const openModal = (vehicle = null) => {
  selectedVehicle.value = vehicle
  showModal.value = true
}

const deleteVehicle = async (id) => {
  if (confirm('Delete this vehicle?')) {
    await api.delete(`/vehicles/${id}`)
    fetchVehicles()
  }
}

onMounted(fetchVehicles)
</script>
