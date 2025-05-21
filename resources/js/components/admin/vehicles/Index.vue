<!-- resources/js/pages/Vehicles.vue -->
<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-xl font-bold">Vehicles</h1>
      <button @click="openModal()" 
     
      class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Vehicle</button>
    </div>
<div class="flex">
  <input v-model="search" placeholder="Search vehicle..." class="input" />
      <select v-model="sortBy">
  <option value="number_plate">Vehicle Number</option>
  <option value="status">Status</option>
</select>

<select v-model="sortDir">
  <option value="asc">Ascending</option>
  <option value="desc">Descending</option>
</select>
<select v-model="statusFilter">
  <option value="">All</option>
  <option value="idle">Idle</option>
  <option value="en_route">En_route</option>
  <option value="maintenance">Maintenance</option>
</select>
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
        <tr v-for="(vehicle, index) in vehicles.data" :key="vehicle.id" class="border-t text-sm">
          <td class="p-2">{{ vehicle.id }}</td>
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
 <!-- Pagination Controls -->
 <div class="flex justify-between items-center mt-4">
      <button
        @click="fetchVehicles(vehicles.current_page - 1)"
        :disabled="vehicles.current_page === 1"
        class="px-3 py-1 border rounded disabled:opacity-50"
      >
        Prev
      </button>
      <span>Page {{ vehicles.current_page }} of {{ vehicles.last_page }}</span>
      <button
        @click="fetchVehicles(vehicles.current_page + 1)"
        :disabled="vehicles.current_page === vehicles.last_page"
        class="px-3 py-1 border rounded disabled:opacity-50"
      >
        Next
      </button>
    </div>
    <VehicleForm
      :vehicle="selectedVehicle"
      :show="showModal"
      @close="showModal = false"
      @saved="fetchVehicles"
    />
  </div>
</template>

<script setup>
import { ref, onMounted,watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'; 
import api from '@/axios'
import VehicleForm from './Form.vue'

const vehicles = ref({ data: [], current_page: 1, last_page: 1 });
const showModal = ref(false)
const selectedVehicle = ref(null)
const search = ref('');
const sortBy = ref('number_plate');
const sortDir = ref('asc');
const statusFilter = ref('');

const debouncedSearch = useDebounceFn(() => {
  fetchVehicles();
}, 500); // 500ms debounce
watch(search, debouncedSearch);
watch([statusFilter,sortDir], () => {
  fetchVehicles();
});

const fetchVehicles = async (page = 1) => {
  const res = await api.get(`/vehicles`, {
      params: {
        page: page,
        search: search.value,
        status:statusFilter.value,
        sort_by:sortBy.value,
        sort_dir:sortDir.value
      },
    });
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
