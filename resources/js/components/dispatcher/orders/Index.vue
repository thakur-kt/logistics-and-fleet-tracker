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
  
      <table class="w-full bg-white shadow rounded text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2">#</th>
            <th class="p-2">Pickup</th>
            <th class="p-2">Dropoff</th>
            <th class="p-2">Status</th>
            <th class="p-2">Vehicle</th>
            <th class="p-2">Driver</th>
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
            <td class="p-2">{{ order.user?.name || '-' }}</td>
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
  import { ref, onMounted ,onBeforeUnmount} from 'vue'
  import api from '@/axios'
  import OrderForm from './Form.vue'
  import { initEcho } from '@/utils/echo';
  import { useAuthStore } from '@/stores/auth';
  const orders = ref([])
  const selectedOrder = ref(null)
  const showModal = ref(false)
  
  const filters = ref({
    pickup: '',
    dropoff: '',
    status: '',
  })
  
  const fetchOrders = async () => {
    const res = await api.get('/delivery-orders', { params: filters.value })
    orders.value = res.data
  }
  
  const openModal = (order = null) => {
    selectedOrder.value = order
    showModal.value = true
  }
  
  const deleteOrder = async (id) => {
    if (confirm('Delete this order?')) {
      await api.delete(` /delivery-orders/${id}`)
      fetchOrders()
    }
  }
onMounted(() => {
  fetchOrders();
  const authStore = useAuthStore();
  const Echo = initEcho(authStore.token);

  const orderId = 2; // or dynamically get the order ID you're tracking

  //note: if not listening run : php artisan queue:work
Echo.private(`orders.${orderId}`)
  .listen('.DeliveryOrderUpdated', (e) => {
    console.log('✅ Order Updated Event:', e.order);
  });
});
onBeforeUnmount(() => {
  Echo.leave(`orders.${orderId}`);
});
  </script>
  