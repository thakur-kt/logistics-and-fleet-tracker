<template>
    <div id="map" class="w-full h-[600px]"></div>
  </template>
  
  <script setup>
  import L from 'leaflet';
  import { onMounted, ref } from 'vue';
  import api from '@/axios';
  
  let map; // Leaflet map instance
  const vehicleMarkers = ref({}); // Store markers by vehicle ID
  
  // Custom icons by status for different vehicle states
  const iconColors = {
    idle: 'blue',
    en_route: 'green',
    completed: 'gray'
  };
  
  // Function to create a custom Leaflet icon based on color
  const createCustomIcon = (color) => {
    return L.divIcon({
      className: 'custom-marker',
      html: `<div style="background-color:${color};width:18px;height:18px;border-radius:50%;border:2px solid white;"></div>`,
      iconSize: [20, 20],
      iconAnchor: [10, 10],
    });
  };
  
  // Fetch all vehicle coordinates from the API and update markers
  const fetchCoordinates = async () => {
    try {
      const response = await api.get('vehicles-coordinates');
      const vehicles = response.data;
  
      vehicles.forEach(vehicle => {
        const id = vehicle.id;
        const coords = [vehicle.lat, vehicle.lng];
        const color = iconColors[vehicle.status] || 'blue';
  
        if (vehicleMarkers.value[id]) {
          // If marker exists, just update its position
          vehicleMarkers.value[id].setLatLng(coords);
        } else {
          // Create a new marker with custom icon and popup
          const marker = L.marker(coords, {
            icon: createCustomIcon(color)
          }).addTo(map).bindPopup(`
            <strong>${vehicle.driver_name}</strong><br />
            ${vehicle.vehicle_number}<br />
            Status: ${vehicle.status}
          `);
          vehicleMarkers.value[id] = marker;
        }
      });
    } catch (err) {
      // Log any errors during fetch
      console.error('Error fetching vehicle coordinates', err);
    }
  };
  
  onMounted(() => {
    map = L.map('map').setView([28.6139, 77.2090],5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
  
    fetchCoordinates();
    setInterval(fetchCoordinates, 30000); // refresh every 10 seconds
  });
  </script>
  
  <style scoped>
  .custom-marker {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  </style>
