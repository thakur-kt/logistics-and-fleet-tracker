<!-- resources/js/components/VehicleForm.vue -->
<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-lg w-full max-w-md p-6 space-y-4">
        <h2 class="text-lg font-semibold">
          {{ vehicle?.id ? 'Edit Vehicle' : 'Add Vehicle' }}
        </h2>
        <form @submit.prevent="saveVehicle">
          <div>
            <label class="block font-semibold mb-1">Plate Number</label>
            <input v-model="form.number_plate" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block font-semibold mb-1">Model</label>
            <input v-model="form.model" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block font-semibold mb-1">Type</label>
            <select v-model="form.status" class="w-full border rounded p-2" required>
            <option value="idle">Idle</option>
            <option value="en_route">En Route</option>
            <option value="maintenance">Maintenance</option>
          </select>
          </div>
          <div class="flex justify-end space-x-2 pt-4">
            <button @click="close" type="button" class="px-4 py-2 text-sm bg-gray-300 rounded">Cancel</button>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded">
              {{ vehicle?.id ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue'
  import api from '@/axios'
  
  // Define props for the component: vehicle object and show boolean
  const props = defineProps({
    vehicle: Object,
    show: Boolean,
  })
  
  // Define emitted events: 'close' for closing modal, 'saved' after save
  const emits = defineEmits(['close', 'saved'])
  
  // Reactive form object for vehicle fields
  const form = ref({
    number_plate: '',
    model: '',
    status: '',
  })
  
  // Watch for changes to the vehicle prop to populate or reset the form
  watch(
    () => props.vehicle,
    (newVal) => {
      if (newVal) {
        // If editing, populate form with vehicle data
        form.value = { ...newVal }
      } else {
        // If adding, reset form fields
        form.value = {
          number_plate: '',
          model: '',
          status: '',
        }
      }
    },
    { immediate: true } // Run immediately on component mount
  )
  
  // Save vehicle (create or update based on presence of id)
  const saveVehicle = async () => {
    try {
      if (props.vehicle?.id) {
        // Update existing vehicle
        await api.put(`/vehicles/${props.vehicle.id}`, form.value)
      } else {
        // Create new vehicle
        await api.post('/vehicles', form.value)
      }
      emits('saved') // Emit saved event for parent to refresh list
      emits('close') // Close the modal
    } catch (e) {
      // Optionally handle error (e.g., show alert)
      // alert('Failed to save vehicle')
    }
  }
  
  // Close the modal dialog
  const close = () => emits('close')
  </script>
