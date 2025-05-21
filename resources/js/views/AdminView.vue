<template>
  <div class="p-6 space-y-6"  v-has-role="['admin']" >
    <h1 class="text-2xl font-bold">👨‍✈️ Admin Dashboard</h1>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="text-gray-500">Total Vehicles</h2>
        <p class="text-2xl font-semibold">{{ stats.vehicles }}</p>
      </div>
      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="text-gray-500">Total Drivers</h2>
        <p class="text-2xl font-semibold">{{ stats.drivers }}</p>
      </div>
      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="text-gray-500">Active Deliveries</h2>
        <p class="text-2xl font-semibold">{{ stats.activeOrders }}</p>
      </div>
      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="text-gray-500">Completed Deliveries</h2>
        <p class="text-2xl font-semibold">{{ stats.completedOrders }}</p>
      </div>
      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="text-gray-500">Assigned Deliveries</h2>
        <p class="text-2xl font-semibold">{{ stats.assignedOrders }}</p>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <TripCharts />
      <VehicleStatusChart />
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow p-4">
      <h2 class="text-lg font-bold mb-2">Recent Trip Activity</h2>
      <div v-if="recentTrips.length" class="overflow-x-auto">
        <table class="min-w-full border">
          <thead class="bg-gray-100 text-left text-sm font-medium">
            <tr>
              <th class="p-2 border">Date</th>
              <th class="p-2 border">Pickup</th>
              <th class="p-2 border">Drop</th>
              <th class="p-2 border">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="trip in recentTrips"
              :key="trip.id"
              class="text-sm text-gray-700 hover:bg-gray-50"
            >
              <td class="p-2 border">{{ trip.created_at }}</td>
              <td class="p-2 border">{{ trip.pickup_location }}</td>
              <td class="p-2 border">{{ trip.dropoff_location }}</td>
              <td class="p-2 border">
                <span
                  :class="{
                     'text-blue-600': trip.status === 'assigned',
                    'text-green-600': trip.status === 'Completed',
                    'text-yellow-600': trip.status === 'Ongoing',
                    'text-red-600': trip.status === 'Cancelled',
                  }"
                >
                  {{ trip.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/axios';
import TripCharts from '@/components/driver/TripCharts.vue';
const stats = ref({
  vehicles: 0,
  drivers: 0,
  activeOrders: 0,
  completedOrders: 0
});

const recentTrips = ref([]);

onMounted(async () => {
  const res = await api.get('/admin/dashboard-stats');
  stats.value = res.data.stats;

  const resp = await api.get('/delivery-orders')
  // Dummy trips (replace with API call if needed)
  recentTrips.value =resp.data;
});
</script>

<style scoped>
</style>
