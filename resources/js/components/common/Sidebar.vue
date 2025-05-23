<template>
  <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white">
    <!-- Sidebar -->
    <aside
    v-if="!shouldHideSidebar" 
    :class="[
        'bg-white dark:bg-gray-800 shadow-md transition-all duration-300 flex flex-col',
        isSidebarExpanded ? 'w-64' : 'w-20'
      ]"
      class="h-screen"
    >
      <!-- Sidebar Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <span v-if="isSidebarExpanded" class="text-lg font-bold">Fleet App</span>
        <button @click="toggleSidebar">
          <component :is="isSidebarExpanded ? ChevronLeft : ChevronRight" class="w-5 h-5" />
        </button>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 p-2 space-y-2">
        <template v-for="item in navItems" :key="item.name">
        <router-link
        v-has-role="item.roles"
        v-if="!item.children"
      
         :to="item.to" 
          class="flex items-center gap-3 p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
          :class="{ 'bg-gray-200 dark:bg-gray-700': route.path === item.to }">
          <component :is="item.icon" class="w-5 h-5" />
          <span v-if="isSidebarExpanded">{{ item.label }}</span>
        </router-link>

      
<!-- <button                                                                                v-has-permission="'create_vehicle'">Add Vehicle</button> -->
        <!-- Collapsible Group -->
        <div v-else>
          <button
          v-has-role="item.roles"
            @click="toggleGroup(item.name)"
            class="flex items-center w-full gap-3 p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
            :class="{'bg-gray-200 dark:bg-gray-700': route.path === item.to ||isGroupActive(item)}"
          >
            <div class="flex items-center gap-3">
              <component :is="item.icon" class="w-5 h-5" />
              <span v-if="isSidebarExpanded" class="text-sm">{{ item.label }}</span>
            </div>
            
            <component
            v-if="item.suffixIcon"
            :is="item.suffixIcon"
            class="w-4 h-4 transition-transform ml-auto" :class="{ 'rotate-90': openGroups[item.name] }"
          />
          </button>

          <div v-show="openGroups[item.name]" class="ml-8 space-y-1" v-if="isSidebarExpanded">
            <router-link
              v-for="child in item.children"
              :key="child.label"
              :to="child.to"
              class="flex block text-sm px-2 py-1 mt-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
              :class="{ 'bg-gray-200 dark:bg-gray-700': route.path === child.to }"
            >
            <span v-if=" isSidebarExpanded">{{ child.label }}</span>
            </router-link>
          </div>
        </div>
      </template>
      </nav>

      <!-- Sidebar Footer -->
      <div class="mt-auto border-t border-gray-200 dark:border-gray-700 p-2">
        <button @click="toggleTheme" class="w-full flex items-center gap-3 p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded">
          <component :is="theme === 'light' ? Moon : Sun" class="w-5 h-5" />
          <span v-if="isSidebarExpanded">Toggle Theme</span>
        </button>
        <button @click="handleLogout" class="w-full flex items-center gap-3 p-2 text-red-500 hover:bg-red-100 dark:hover:bg-red-900 rounded">
          <LogOut class="w-5 h-5" />
          <span v-if="isSidebarExpanded">Logout</span>
        </button>
      </div>
    </aside>

    <!-- Content -->
    <main class="flex-1  transition-all duration-300">
      <div class="p-6">
        <router-view />
      </div>
    </main>
  </div>
</template>
<script setup>
import { ref, watchEffect, onMounted, computed, defineAsyncComponent } from 'vue'
import { Menu, LogOut, Sun, Moon, Home, Truck, Users, Settings, ChevronLeft, ChevronRight, ChevronDown } from 'lucide-vue-next'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth';
import { loadIcon } from '@/utils/lazyIcon'

// Get the current route for active link highlighting
const route = useRoute()
// Sidebar expanded/collapsed state, persisted in localStorage
const isSidebarExpanded = ref(JSON.parse(localStorage.getItem('sidebarExpanded') || 'true'))
// Theme state (light/dark), persisted in localStorage
const theme = ref(localStorage.getItem('theme') || 'light')

// List of route names where sidebar should be hidden (e.g., login/register)
const hideSidebarRoutes = ['login', 'register']; // by name

// Computed property to determine if sidebar should be hidden
const shouldHideSidebar = computed(() => {
  return hideSidebarRoutes.includes(route.name);
});

// Import navigation items (array of sidebar links/groups)
import { navItems } from '../layout/NavItems.js' 
// Track which sidebar groups are open (for collapsible menus)
const openGroups = ref({}) 
// Access the authentication store for role-based navigation
const auth = useAuthStore();

// Toggle a sidebar group open/closed
function toggleGroup(name) {
  openGroups.value[name] = !openGroups.value[name]
}

// Check if any child route in a group is active (for highlighting)
function isGroupActive(group) {
  return group.children?.some(child => route.path === child.to)
}

// Filter navigation items based on user roles
const filteredNavItems = computed(() => {
  if (!auth.roles.length) return [];
  return navItems.filter(item => 
    item.roles.some(role => auth.roles.includes(role))
  );
});

// Toggle sidebar expanded/collapsed and persist state
const toggleSidebar = () => {
  isSidebarExpanded.value = !isSidebarExpanded.value
  localStorage.setItem('sidebarExpanded', JSON.stringify(isSidebarExpanded.value))
}

// Toggle theme (light/dark) and persist state
const toggleTheme = () => {
  theme.value = theme.value === 'light' ? 'dark' : 'light'
  localStorage.setItem('theme', theme.value)
}

// Handle logout by calling the auth store's logout action
const handleLogout = async () => {
  await auth.logout()
}

// Watch theme and update the document's class for dark mode
watchEffect(() => {
  document.documentElement.classList.toggle('dark', theme.value === 'dark')
})

// Set initial theme on mount
onMounted(() => {
  document.documentElement.classList.toggle('dark', theme.value === 'dark')
})
</script>


