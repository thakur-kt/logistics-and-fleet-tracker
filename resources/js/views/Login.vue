<template>
 
  <form @submit.prevent="login" class="max-w-md mx-auto mt-12 p-6 bg-white rounded-md shadow-md border-t-4 border-indigo-600">
  <h2 class="text-2xl font-semibold mb-6 text-center">Login to Your Account</h2>

  <div class="mb-4">
    <label for="email" class="block mb-1 font-medium text-gray-700">Email address</label>
    <input
      v-model="form.email"
      type="email"
      id="email"
      name="email"
      placeholder="you@example.com"
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
      required
    />
  </div>

  <div class="mb-6">
    <label for="password" class="block mb-1 font-medium text-gray-700">Password</label>
    <input
      v-model="form.password"
      type="password"
      id="password"
      name="password"
      placeholder="Enter your password"
      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
      required
      minlength="6"
    />
  </div>

  <button
    type="submit"
    class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200"
  >
    Login
  </button>
</form>

</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Reactive form object for login fields
const form = ref({
    email: '',
    password: '',
})

// Access the authentication store (Pinia)
const auth = useAuthStore()
// Access the Vue Router instance for navigation
const router = useRouter()

// Login function to submit form data to the auth store
const login = async () => {
  try {
    // Call the login action from the auth store with form data
    await auth.login(form.value)
    // Optionally, redirect the user after successful login
    // router.push('/')
  } catch (err) {
    // Show an alert if login fails
    alert('Login failed')
  }
}
</script>
