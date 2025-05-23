<!-- pages/DeliveryOrders.vue -->
<template>
    <div class="p-6">
      <div class="flex justify-between mb-4">
        <h1 class="text-xl font-bold">Delivery Orders</h1>
        <button @click="openModal()"
        v-has-permission="'create_orders'"
        class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Order</button>
      </div>
  
      <div class="mb-4 flex space-x-4">
        <input v-model="filters.pickup" placeholder="Pickup..." class="form-input" />
        <input v-model="filters.dropoff" placeholder="Dropoff..." class="form-input" />
        <select v-model="filters.status" class="form-select">
          <option value="">All</option>
          <option value="pending">Pending</option>
          <option value="assigned">Assigned</option>
          <option value="in_transit">In Transit</option>
          <option value="delivered">Delivered</option>
        </select>
        <button @click="fetchOrders" class="bg-gray-200 px-3 rounded">Filter</button>
      </div>
  
      <table class="w-full bg-white shadow rounded text-sm text-center">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2">#</th>
            <th class="p-2">Pickup</th>
            <th class="p-2">Dropoff</th>
            <th class="p-2">Status</th>
            <th class="p-2">Vehicle</th>
            <th class="p-2">Driver</th>
            <th class="p-2">Tracking Link</th>
            <th class="p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(order, i) in orders" :key="order.id" class="border-t">
            <td class="p-2">{{ i + 1 }}</td>
            <td class="p-2">{{ order.pickup_location }}</td>
            <td class="p-2">{{ order.dropoff_location }}</td>
            <td class="p-2 capitalize">{{ order.status }}</td>
            <td class="p-2">{{ order.vehicle?.number_plate || '-' }}</td>
            <td class="p-2">{{ order.driver?.name || '-' }}</td>
            <td class="p-2"><a class="text-blue-600" :href="'/driver/live-tracking/'+order.vehicle.id">Track</a></td>
            <td class="p-2 space-x-2">
              <button class="text-blue-600" @click="openModal(order)">Edit</button>
              <button class="text-red-600" @click="deleteOrder(order.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
  
      <OrderForm
        :order="selectedOrder"
        :show="showModal"
        @close="showModal = false"
        @saved="fetchOrders"
      />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onBeforeUnmount } from 'vue'
  import api from '@/axios'
  import OrderForm from './Form.vue'
  import { initEcho } from '@/utils/echo';
  import { useAuthStore } from '@/stores/auth';

  // Reactive array to hold all delivery orders
  const orders = ref([])
  // Holds the currently selected order for editing
  const selectedOrder = ref(null)
  // Controls visibility of the order form modal
  const showModal = ref(false)

  // Filters for searching and filtering orders
  const filters = ref({
    pickup: '',
    dropoff: '',
    status: '',
  })

  // Fetch delivery orders from the API with current filters
  const fetchOrders = async () => {
    const res = await api.get('/delivery-orders', { params: filters.value })
    orders.value = res.data
  }

  // Open the modal for adding or editing an order
  const openModal = (order = null) => {
    selectedOrder.value = order
    showModal.value = true
  }

  // Delete an order by ID and refresh the list
  const deleteOrder = async (id) => {
    if (confirm('Delete this order?')) {
      await api.delete(`/delivery-orders/${id}`)
      fetchOrders()
    }
  }

  // Access the authentication store for user token
  const authStore = useAuthStore();
  // Initialize Echo for real-time event listening, passing the auth token
  const Echo = initEcho(authStore.token);
  // Hardcoded orderId and vehicleId for demonstration (replace with dynamic values if needed)
  const orderId = 2;
  const vehicleId = 1;

  // Lifecycle hook: runs when component is mounted
  onMounted(() => {
    fetchOrders();
    // Note: if not listening run : php artisan queue:work redis
    // Listen for real-time order update events on a private channel
    Echo.private(`orders.${orderId}`)
      .listen('.DeliveryOrderUpdated', (e) => {
        console.log('✅ Order Updated Event:', e.order);
      });
    // Listen for real-time vehicle tracking events on a private channel
    Echo.private(`vehicle.${vehicleId}`)
      .listen('.TrackingLive', (e) => {
        console.log('✅ TrackingLive Event:', e);
      });
  });

  // Cleanup Echo listeners when component is about to be unmounted
  onBeforeUnmount(() => {
    Echo.leave(`orders.${orderId}`);
    Echo.leave(`vehicle.${vehicleId}`);
  });
  </script>
