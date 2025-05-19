<template>
  <div class="p-4 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-6" v-has-role="['driver']">👨‍✈️ Driver Dashboard</h1>
    <h1 class="text-2xl font-bold mb-6" v-has-role="['admin']">👨‍✈️ Admin Dashboard</h1>

    <!-- Profile Summary -->
    <div class="grid gap-4 md:grid-cols-3" v-has-role="['driver']">
      <!-- Driver Info -->
      <div class="col-span-2 bg-white p-4 rounded shadow">
        <h2 class="text-lg font-semibold mb-2">Profile</h2>
        <ul class="space-y-1 text-gray-700">
          <li><strong>Name:</strong> {{ driver.name }}</li>
          <li><strong>Phone:</strong> {{ driver.phone }}</li>
          <li><strong>Vehicle Number:</strong> {{ driver.vehicle_number }}</li>
          <li>
            <strong>Status:</strong>
            <span
              :class="{
                'text-green-600 font-semibold': driver.status === 'Active',
                'text-red-600 font-semibold': driver.status !== 'Active',
              }"
            >
              {{ driver.status }}
            </span>
          </li>
        </ul>
      </div>

      <!-- Vehicle Info -->
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-semibold mb-2">Vehicle Info</h2>
        <div class="text-gray-700">
          <p><strong>Type:</strong> {{ driver.vehicle_type }}</p>
          <p><strong>Model:</strong> {{ driver.vehicle_model }}</p>
          <p><strong>Capacity:</strong> {{ driver.capacity }}</p>
        </div>
      </div>
    </div>
    <div class="mt-6 bg-white p-4 rounded shadow" v-has-role="['driver']">
    <TripCharts class="mt-10" />
  </div>
    <!-- Recent Trips -->
    <div class="mt-6 bg-white p-4 rounded shadow" v-has-role="['driver']">
      <h2 class="text-lg font-semibold mb-4">Recent Trips</h2>
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
      <div v-else class="text-gray-500">No recent trips found.</div>
    </div>
    
  </div>
</template>
  
  <script setup>
  import { onMounted ,onBeforeUnmount,ref} from 'vue'
  import { initEcho } from '@/utils/echo';
  import { useAuthStore } from '@/stores/auth';
import { useDriverStore } from '@/stores/driver';
import api from '@/axios';
import TripCharts from '@/components/driver/TripCharts.vue';
const driverStore = useDriverStore();
const driver = ref({
  name: '',
  phone: '',
  vehicle_number: '',
  status: 'Inactive',
  vehicle_type: 'Truck',
  vehicle_model: 'Tata 407',
  capacity: '2 Tons',
});
const recentTrips = ref([]);
  const authStore = useAuthStore();
  const Echo = initEcho(authStore.token);
  const vehicleId=1;
onMounted(async () => {
  await driverStore.fetchProfile();
  driver.value = {
    ...driverStore.profile,
    vehicle_type: 'Truck',
    vehicle_model: 'Tata 407',
    capacity: '2 Tons',
  };
  const res = await api.get('/delivery-orders')
  // Dummy trips (replace with API call if needed)
  recentTrips.value =res.data;
  Echo.private(`vehicle.${vehicleId}`)
  .listen('.TrackingLive', (e) => {
    console.log('✅ TrackingLive Event:', e);
  });
});
onBeforeUnmount(() => {
  Echo.leave(`vehicle.${vehicleId}`);
});
  </script>
  
  