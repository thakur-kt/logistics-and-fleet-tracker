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
  
  const props = defineProps({
    vehicle: Object,
    show: Boolean,
  })
  
  const emits = defineEmits(['close', 'saved'])
  
  const form = ref({
    number_plate: '',
    model: '',
    status: '',
  })
  
  watch(
    () => props.vehicle,
    (newVal) => {
      if (newVal) {
        form.value = { ...newVal }
      } else {
        form.value = {
          number_plate: '',
    model: '',
    status: '',
        }
      }
    },
    { immediate: true }
  )
  
  const saveVehicle = async () => {
    try {
      if (props.vehicle?.id) {
        await api.put(`/vehicles/${props.vehicle.id}`, form.value)
      } else {
        await api.post('/vehicles', form.value)
      }
      emits('saved')
      emits('close')
    } catch (e) {
      // alert('Failed to save vehicle')
    }
  }
  
  const close = () => emits('close')
  </script>
  