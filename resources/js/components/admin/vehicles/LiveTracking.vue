<template>
    <div>
      <label class="flex items-center space-x-2 mb-4">
        VID-{{ vehicleId }}
        <input type="checkbox" v-model="useMockLocation" @change="restartTracking" />
        <span>Use Mock Location (Dev Mode)</span>
      </label>
    </div>
  </template>
  
  <script setup>
  import { onMounted, onUnmounted,ref,onBeforeUnmount } from 'vue';
  import { useRoute } from 'vue-router';
//   import { useAuthStore } from '@/stores/auth';
  import api from '@/axios';
  import indiaCities from './india-latlongs.json'; // If stored locally
  const route = useRoute();
  const vehicleId = route.params.vehicleId;
//   const authStore = useAuthStore();
  const useMockLocation = ref(false); // 👈 Toggle for dev mode
let watchId = null;

const startTracking = () => {
  if (useMockLocation.value) {
    // 🚧 Mock location every 5 seconds
    const intervalId = setInterval(() => {
      // Randomly pick one
const randomCity = indiaCities[Math.floor(Math.random() * indiaCities.length)];

console.log(`Start from ${randomCity.city}:`, randomCity.lat, randomCity.lng);
      const mockCoords = {
        latitude: randomCity.lat,
        longitude: randomCity.lng,
      };
      console.log('📍 Mock Location:', mockCoords);
      sendLocation(mockCoords);
    },10000);

    watchId = intervalId;
  } else {
    if ('geolocation' in navigator) {
      watchId = navigator.geolocation.watchPosition(
        (position) => {
          const coords = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
          };
          console.log('📍 Real Location:', coords);
          sendLocation(coords);
        },
        (error) => {
          console.error('❌ Geolocation error:', error);
        },
        {
          enableHighAccuracy: true,
          timeout: 15000,
          maximumAge: 0,
        }
      );
    } else {
      console.warn('❌ Geolocation not supported');
    }
  }
};

const stopTracking = () => {
  if (useMockLocation.value) {
    clearInterval(watchId);
  } else if (navigator.geolocation && watchId !== null) {
    navigator.geolocation.clearWatch(watchId);
  }
};

const sendLocation = (coords) => {
    coords.vehicle_id= vehicleId;
  api.post(`/vehicles/${vehicleId}/location`, coords);
};

const restartTracking = () => {
  stopTracking();
  startTracking();
};

onMounted(() => {
  startTracking();
});

onBeforeUnmount(() => {
  stopTracking();
});
  </script>
  