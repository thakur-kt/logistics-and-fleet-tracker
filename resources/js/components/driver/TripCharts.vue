<template>
    <div class="grid md:grid-cols-2 gap-6">
      <!-- Line Chart -->
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-semibold mb-2">📈 Trips Over Time</h2>
        <Line :data="lineData" :options="lineOptions" />
      </div>
  
      <!-- Doughnut Chart -->
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-semibold mb-2">📊 Trip Status Summary</h2>
        <Doughnut :data="doughnutData" :options="doughnutOptions" />
      </div>
    </div>
  </template>
  
  <script setup>
  import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    ArcElement,
  } from 'chart.js';
  
  import { Line, Doughnut } from 'vue-chartjs';
  import { ref } from 'vue';
  
  ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    ArcElement
  );
  
  const lineData = ref({
    labels: ['May 13', 'May 14', 'May 15', 'May 16', 'May 17'],
    datasets: [
      {
        label: 'Trips',
        data: [2, 4, 3, 5, 2],
        borderColor: '#3B82F6',
        backgroundColor: 'rgba(59, 130, 246, 0.5)',
        fill: true,
        tension: 0.4,
      },
    ],
  });
  
  const lineOptions = ref({
    responsive: true,
    plugins: {
      legend: { position: 'top' },
      title: { display: true, text: 'Trips Per Day' },
    },
  });
  
  const doughnutData = ref({
    labels: ['Completed', 'Ongoing', 'Cancelled'],
    datasets: [
      {
        label: 'Trips',
        data: [10, 3, 2],
        backgroundColor: ['#10B981', '#FBBF24', '#EF4444'],
      },
    ],
  });
  
  const doughnutOptions = ref({
    responsive: true,
    plugins: {
      legend: { position: 'bottom' },
      title: { display: true, text: 'Trip Status Distribution' },
    },
  });
  </script>
  