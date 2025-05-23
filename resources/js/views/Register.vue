<!-- src/views/Register.vue -->
<template>
    <form @submit.prevent="register" class="max-w-md mx-auto mt-12 p-6 bg-white rounded-md shadow-md border-t-4 border-indigo-600">
  <h2 class="text-2xl font-semibold mb-6 text-center">Create an Account</h2>

  <div class="mb-4">
    <label for="name" class="block mb-1 font-medium text-gray-700">Full Name</label>
    <input
      type="text"
      id="name"
      v-model="form.name"
      name="name"
      placeholder="Your full name"
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
      required
    />
  </div>

  <div class="mb-4">
    <label for="email" class="block mb-1 font-medium text-gray-700">Email address</label>
    <input
      type="email"
      id="email"
      v-model="form.email"
      name="email"
      placeholder="you@example.com"
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
      required
    />
  </div>
  <div class="mb-4">
    <label for="role" class="block mb-1 font-medium text-gray-700">Select Role</label>
    <select
      v-model="form.role"
      id="role"
      name="role"
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
      required
    >
      <option value="" disabled selected>Select a role</option>
      <option value="admin">Admin</option>
      <option value="dispatcher">Dispatcher</option>
      <option value="driver">Driver</option>
    </select>
  </div>
  <div class="mb-4">
    <label for="password" class="block mb-1 font-medium text-gray-700">Password</label>
    <input
      type="password"
      v-model="form.password"
      id="password"
      name="password"
      placeholder="Enter password"
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
      required
      minlength="6"
    />
  </div>

  <div class="mb-6">
    <label for="password_confirmation" class="block mb-1 font-medium text-gray-700">Confirm Password</label>
    <input
      type="password"
      id="password_confirmation"
      v-model="form.password_confirmation"
      name="password_confirmation"
      placeholder="Confirm your password"
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
      required
      minlength="6"
    />
    
  </div>

  <button
    type="submit"
    class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200"
  >
    Register
  </button>
</form>

  </template>
  
  <script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'

// Reactive form object for registration fields
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'driver', // Default role is driver
})

// Access the authentication store (Pinia)
const auth = useAuthStore()

// Register function to submit form data to the auth store
const register = async () => {
  try {
    // Call the register action from the auth store with form data
    await auth.register(form.value)
    // Optionally, show a success message or redirect after registration
    alert('Registered! Please verify your email.')
  } catch (err) {
    // Log any registration errors to the console
    console.error(err)
  }
}
</script>
