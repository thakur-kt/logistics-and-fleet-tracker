<!-- components/OrderForm.vue -->
<template>
    <div v-if="show" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg w-full max-w-lg p-6 space-y-4">
        <h2 class="text-lg font-semibold">
          {{ order?.id ? 'Edit Delivery Order' : 'Create Delivery Order' }}
        </h2>
  
        <form @submit.prevent="saveOrder">
          <div>
            <label>Pickup Address</label>
            <input v-model="form.pickup_location" class="form-input w-full" required />
          </div>
          <div>
            <label>Dropoff Address</label>
            <input v-model="form.dropoff_location" class="form-input w-full" required />
          </div>
          <div>
            <label>Status</label>
            <select v-model="form.status" class="form-select w-full">
              <option value="pending">Pending</option>
              <option value="assigned">Assigned</option>
              <option value="in_transit">In Transit</option>
              <option value="delivered">Delivered</option>
            </select>
          </div>
          <div>
            <label>Assign Vehicle</label>
            <select v-model="form.vehicle_id" class="form-select w-full">
              <option :value="null">--</option>
              <option v-for="v in vehicles" :key="v.id" :value="v.id">{{ v.number_plate }}</option>
            </select>
          </div>
          <div v-if="order?.id">
            <label>Assign Driver</label>
            <select v-model="form.driver_id" class="form-select w-full">
              <option :value="null">--</option>
              <option v-for="d in drivers" :key="d.id" :value="d.id">{{ d.email }}</option>
            </select>
          </div>
  
          <div class="flex justify-end space-x-2 pt-4">
            <button @click="$emit('close')" type="button" class="px-4 py-2 bg-gray-300 text-sm rounded">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded">
              {{ order?.id ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch, onMounted } from 'vue'
  import api from '@/axios'
  
  // Define props for modal visibility and the order being edited/created
  const props = defineProps({
    show: Boolean,
    order: Object,
  })
  
  // Define emitted events: 'close' for closing modal, 'saved' after save
  const emits = defineEmits(['close', 'saved'])
  
  // Reactive form object for delivery order fields
  const form = ref({
    pickup_location: '',
    dropoff_location: '',
    status: 'pending',
    vehicle_id: null,
    driver_id: null,
  })
  
  // Arrays to hold available vehicles and drivers for selection
  const vehicles = ref([])
  const drivers = ref([])
  
  // Watch for changes to the order prop to populate or reset the form
  watch(
    () => props.order,
    (val) => {
      if (val) {
        // If editing, populate form with order data
        form.value = { ...val }
      } else {
        // If creating, reset form fields
        form.value = {
          pickup_location: '',
          dropoff_location: '',
          status: 'pending',
          vehicle_id: null,
          driver_id: null,
        }
      }
    },
    { immediate: true } // Run immediately on component mount
  )
  
  // Fetch vehicles and drivers when component is mounted
  onMounted(async () => {
    const [vehicleRes, driverRes] = await Promise.all([
      api.get('/vehicles'),
      api.get('/drivers'),
    ])
    vehicles.value = vehicleRes.data.data // Assign fetched vehicles
    drivers.value = driverRes.data        // Assign fetched drivers
  })
  
  // Save order (create or update based on presence of id)
  const saveOrder = async () => {
    if (props.order?.id) {
      // Update existing order
      await api.put(`/delivery-orders/${props.order.id}`, form.value)
    } else {
      // Create new order
      await api.post('/delivery-orders', form.value)
    }
    emits('saved') // Emit saved event for parent to refresh list
    emits('close') // Close the modal
  }
  </script>
