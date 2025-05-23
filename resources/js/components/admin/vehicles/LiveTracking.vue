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
  import { onMounted, onUnmounted, ref, onBeforeUnmount } from 'vue';
  import { useRoute } from 'vue-router';
//   import { useAuthStore } from '@/stores/auth';
  import api from '@/axios';
  import indiaCities from './india-latlongs.json'; // If stored locally

  const route = useRoute();
  const vehicleId = route.params.vehicleId;
//   const authStore = useAuthStore();
  const useMockLocation = ref(false); // 👈 Toggle for dev mode
  let watchId = null;

  // Function to start tracking (either mock or real geolocation)
  const startTracking = () => {
    if (useMockLocation.value) {
      // 🚧 If using mock location, send a random city location every 10 seconds
      const intervalId = setInterval(() => {
        // Randomly pick a city from the list
        const randomCity = indiaCities[Math.floor(Math.random() * indiaCities.length)];
        console.log(`Start from ${randomCity.city}:`, randomCity.lat, randomCity.lng);
        const mockCoords = {
          latitude: randomCity.lat,
          longitude: randomCity.lng,
        };
        console.log('📍 Mock Location:', mockCoords);
        sendLocation(mockCoords);
      }, 10000);

      watchId = intervalId;
    } else {
      // Use real geolocation if available
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
            // Handle geolocation errors
            console.error('❌ Geolocation error:', error);
          },
          {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0,
          }
        );
      } else {
        // Warn if geolocation is not supported
        console.warn('❌ Geolocation not supported');
      }
    }
  };

  // Function to stop tracking (clear interval or geolocation watch)
  const stopTracking = () => {
    if (useMockLocation.value) {
      clearInterval(watchId);
    } else if (navigator.geolocation && watchId !== null) {
      navigator.geolocation.clearWatch(watchId);
    }
  };

  // Function to send location data to the backend API
  const sendLocation = (coords) => {
    coords.vehicle_id = vehicleId;
    api.post(`/vehicles/${vehicleId}/location`, coords);
  };

  // Restart tracking when the mock toggle changes
  const restartTracking = () => {
    stopTracking();
    startTracking();
  };

  // Start tracking when component is mounted
  onMounted(() => {
    startTracking();
  });

  // Stop tracking when component is about to be unmounted
  onBeforeUnmount(() => {
    stopTracking();
  });
  </script>
