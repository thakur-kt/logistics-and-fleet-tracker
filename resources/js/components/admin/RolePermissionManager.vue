<template>
  <div class="p-6 max-w-4xl mx-auto space-y-6">
    <h2 class="text-xl font-bold">Role & Permission Manager</h2>

    <div class="grid grid-cols-2 gap-6">
      <div>
        <h3 class="font-semibold mb-2">Create Role</h3>
        <input v-model="newRole" placeholder="Role name" class="border rounded px-3 py-1 w-full" />
        <button @click="createRole" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded">Create Role</button>
      </div>

      <div>
        <h3 class="font-semibold mb-2">Create Permission</h3>
        <input v-model="newPermission" placeholder="Permission name" class="border rounded px-3 py-1 w-full" />
        <button @click="createPermission" class="mt-2 px-3 py-1 bg-green-600 text-white rounded">Create Permission</button>
      </div>
    </div>

    <div>
      <h3 class="font-semibold mt-6">Assign Permission to Role</h3>
      <div class="flex gap-3 mt-2">
        <select v-model="selectedRole" class="border rounded p-1">
          <option disabled value="">Select Role</option>
          <option v-for="role in roles" :key="role.name" :value="role.name">{{ role.name }}</option>
        </select>
        <select v-model="selectedPermission" class="border rounded p-1">
          <option disabled value="">Select Permission</option>
          <option v-for="perm in permissions" :key="perm" :value="perm">{{ perm }}</option>
        </select>
        <button @click="assignPermission" class="bg-indigo-600 text-white px-3 py-1 rounded">Assign</button>
      </div>
    </div>

    <div class="mt-6">
      <h3 class="font-semibold">Roles & Their Permissions</h3>
      <ul class="mt-3 space-y-3">
        <li v-for="role in roles" :key="role.name" class="border p-3 rounded shadow">
          <div class="font-bold">{{ role.name }}</div>
          <div class="flex flex-wrap gap-2 mt-2">
            <span
              v-for="perm in role.permissions"
              :key="perm.name"
              class="bg-gray-100 text-sm px-2 py-1 rounded-full flex items-center gap-1"
            >
              {{ perm.name }}
              <button @click="revokePermission(role.name, perm.name)" class="text-red-500 font-bold">×</button>
            </span>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '@/axios'

const roles = ref([])
const permissions = ref([])
const newRole = ref('')
const newPermission = ref('')
const selectedRole = ref('')
const selectedPermission = ref('')

const fetchAll = async () => {
  const [r, p] = await Promise.all([
    api.get('/admin/roles'),
    api.get('/admin/permissions'),
  ])
  roles.value = r.data
  permissions.value = p.data
}

const createRole = async () => {
  if (!newRole.value) return
  await api.post('/admin/roles', { name: newRole.value })
  newRole.value = ''
  await fetchAll()
}

const createPermission = async () => {
  if (!newPermission.value) return
  await api.post('/admin/permissions', { name: newPermission.value })
  newPermission.value = ''
  await fetchAll()
}

const assignPermission = async () => {
  if (!selectedRole.value || !selectedPermission.value) return
  await api.post('/admin/roles/assign-permission', {
    role: selectedRole.value,
    permission: selectedPermission.value
  })
  await fetchAll()
}

const revokePermission = async (role, perm) => {
  await api.post('/admin/roles/remove-permission', {
    role,
    permission: perm
  })
  await fetchAll()
}

onMounted(fetchAll)
</script>