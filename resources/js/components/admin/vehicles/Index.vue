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
import { ref, onMounted, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'; 
import api from '@/axios'
import VehicleForm from './Form.vue'

// Reactive object to hold paginated vehicles data
const vehicles = ref({ data: [], current_page: 1, last_page: 1 });
// Controls visibility of the vehicle form modal
const showModal = ref(false)
// Holds the vehicle being edited (null for add)
const selectedVehicle = ref(null)
// Search input for filtering vehicles
const search = ref('');
// Sorting field and direction
const sortBy = ref('number_plate');
const sortDir = ref('asc');
// Status filter for vehicles
const statusFilter = ref('');

// Debounced search to avoid excessive API calls
const debouncedSearch = useDebounceFn(() => {
  fetchVehicles();
}, 500); // 500ms debounce

// Watch search input and trigger debounced fetch
watch(search, debouncedSearch);
// Watch status and sort changes and fetch immediately
watch([statusFilter, sortDir], () => {
  fetchVehicles();
});

// Fetch vehicles from API with current filters and pagination
const fetchVehicles = async (page = 1) => {
  const res = await api.get(`/vehicles`, {
      params: {
        page: page,
        search: search.value,
        status: statusFilter.value,
        sort_by: sortBy.value,
        sort_dir: sortDir.value
      },
    });
  vehicles.value = res.data
}

// Open modal for adding or editing a vehicle
const openModal = (vehicle = null) => {
  selectedVehicle.value = vehicle
  showModal.value = true
}

// Delete a vehicle by ID
const deleteVehicle = async (id) => {
  if (confirm('Delete this vehicle?')) {
    await api.delete(`/vehicles/${id}`)
    fetchVehicles()
  }
}

// Fetch vehicles on component mount
onMounted(fetchVehicles)
</script>
