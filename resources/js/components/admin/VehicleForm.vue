<template>
    <div class="p-4 max-w-md">
      <h2 class="text-xl font-bold mb-4">
        {{ isEdit ? 'Edit Vehicle' : 'Add Vehicle' }}
      </h2>
  
      <form @submit.prevent="handleSubmit">
        <!-- Number Plate -->
        <div class="mb-4">
          <label class="block font-semibold mb-1">Number Plate</label>
          <input v-model="form.number_plate" type="text" class="w-full border rounded p-2" required />
        </div>
  
        <!-- Model -->
        <div class="mb-4">
          <label class="block font-semibold mb-1">Model</label>
          <input v-model="form.model" type="text" class="w-full border rounded p-2" required />
        </div>
  
        <!-- Status -->
        <div class="mb-4">
          <label class="block font-semibold mb-1">Status</label>
          <select v-model="form.status" class="w-full border rounded p-2" required>
            <option value="idle">Idle</option>
            <option value="en_route">En Route</option>
            <option value="maintenance">Maintenance</option>
          </select>
        </div>
  
        <!-- Assign Driver -->
        <div class="mb-4">
          <label class="block font-semibold mb-1">Assign Driver</label>
          <select v-model="form.user_id" class="w-full border rounded p-2">
            <option value="">Unassigned</option>
            <option v-for="driver in drivers" :key="driver.id" :value="driver.id">
              {{ driver.name }}
            </option>
          </select>
        </div>
  
        <!-- Submit -->
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
          {{ isEdit ? 'Update' : 'Create' }}
        </button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  
  const props = defineProps({
    vehicle: Object, // optional, passed when editing
  })
  
  const router = useRouter()
  const isEdit = ref(!!props.vehicle)
  const form = ref({
    number_plate: '',
    model: '',
    status: 'idle',
    user_id: ''
  })
  const drivers = ref([])
  
  onMounted(() => {
    if (isEdit.value) {
      form.value = { ...props.vehicle }
    }
    fetchDrivers()
  })
  
  const fetchDrivers = async () => {
    try {
      const res = await axios.get('/api/drivers') // expected route
      drivers.value = res.data
    } catch (err) {
      console.error('Error fetching drivers:', err)
    }
  }
  
  const handleSubmit = async () => {
    try {
      if (isEdit.value) {
        await axios.put(`/api/vehicles/${props.vehicle.id}`, form.value)
      } else {
        await axios.post('/api/vehicles', form.value)
      }
      router.push('/vehicles') // or emit event to parent to refresh
    } catch (err) {
      console.error('Error saving vehicle:', err)
    }
  }
  </script>
  