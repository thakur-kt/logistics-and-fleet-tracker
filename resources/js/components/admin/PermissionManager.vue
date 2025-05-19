<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h2 class="text-xl font-bold mb-4">User Permission Management</h2>
    <div v-if="loading">Loading...</div>
    <table v-else class="w-full border text-left">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2">User</th>
          <th class="p-2">Email</th>
          <th class="p-2">Permissions</th>
          <th class="p-2">Manage</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id" class="border-b">
          <td class="p-2">{{ user.name }}</td>
          <td class="p-2">{{ user.email }}</td>
          <td class="p-2">
            <div class="flex flex-wrap gap-1">
              <span
                v-for="perm in user.userPermissions"
                :key="perm"
                class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs"
              >
                {{ perm }}
              </span>
            </div>
          </td>
          <td class="p-2">
            <select
              class="border rounded p-1"
              @change="e => assignPermission(user.id, e.target.value)"
            >
              <option disabled selected>Assign Permission</option>
              <option v-for="perm in permissions" :key="perm" :value="perm">
                {{ perm }}
              </option>
            </select>
            <div class="mt-2">
              <button
                v-for="perm in user.userPermissions"
                :key="perm"
                class="text-red-500 text-xs mr-2"
                @click="revokePermission(user.id, perm)"
              >
                ✕ {{ perm }}
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '@/axios'

const users = ref([])
const permissions = ref([])
const loading = ref(true)

const fetchData = async () => {
  const [userRes, permissionRes] = await Promise.all([
    api.get('/admin/users'),
    api.get('/admin/permissions')
  ])
  users.value = userRes.data
  permissions.value = permissionRes.data

  // fetch each user's permissions
  await Promise.all(users.value.map(async user => {
    const res = await api.get(`/admin/users/${user.id}/permissions`)
    user.userPermissions = res.data
  }))

  loading.value = false
}

const assignPermission = async (userId, permission) => {
  await api.post(`/admin/users/${userId}/assign-permission`, { permission })
  await fetchData()
}

const revokePermission = async (userId, permission) => {
  await api.post(`/admin/users/${userId}/revoke-permission`, { permission })
  await fetchData()
}

onMounted(fetchData)
</script>