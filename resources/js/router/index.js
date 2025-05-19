import AdminView from '../views/AdminView.vue';
import UserRoleManager from '../components/admin/UserRoleManager.vue';
import PermissionManager from '../components/admin/PermissionManager.vue';
import RolePermissionManager from '../components/admin/RolePermissionManager.vue';
import VehiclesView from '../components/admin/vehicles/Index.vue';
import DeliveryOrders from '../components/dispatcher/orders/Index.vue';
import LiveTracking from '../components/admin/vehicles/LiveTracking.vue';
import DriverProfile from '../components/driver/DriverProfile.vue';
// export default router;

import Home from '@/views/Home.vue'
import Register from '@/views/Register.vue'
import Login from '@/views/Login.vue'
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
// import { useTrackingStore } from '@/stores/tracking';

const routes = [
  { path: '/', component: Home ,name:'home',meta: { requiresAuth: true, role: ['admin' ]}},
  { path: '/login', component: Login ,  name: 'login' ,beforeEnter: (to, from, next) => {
    const auth = useAuthStore()
    if (auth.token) {
      next({ name: 'home' })  // or wherever your home/dashboard is
    } else {
      next()
    }
  },},
  { path: '/register', component: Register,name: 'register', beforeEnter: (to, from, next) => {
    const auth = useAuthStore()
    if (auth.token) {
      next({ name: 'home' })  // or wherever your home/dashboard is
    } else {
      next()
    }
  }, },
  {
    path: '/admin',
    component: AdminView,
    meta: { requiresAuth: true, role: ['admin' ]}
  },
  {
    path: '/roles-manager',
    component: UserRoleManager,
    meta: { requiresAuth: true, role: ['admin' ]}
  },
  {
    path: '/permission-manager',
    component: PermissionManager,
    meta: { requiresAuth: true, role: ['admin' ]}
  },
  {
    path: '/roles-permission-manager',
    component: RolePermissionManager,
    meta: { requiresAuth: true, role: ['admin' ]}
  },
  {
    path: '/vehicles',
    component: VehiclesView,
    meta: { requiresAuth: true, role: ['admin' ]}
  },
  {
    path: '/delivery-orders',
    component: DeliveryOrders,
    meta: { requiresAuth: true, role: ['admin' ]}
  },
 {
  path: '/driver/live-tracking/:vehicleId',
  name: 'DriverLiveTracking',
  component: LiveTracking,
  meta: { requiresAuth: true }, 
 },
  {
    path: '/driver-profile',
    name: 'driver-profile',
    component: DriverProfile,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()

  // const trackingStore = useTrackingStore();

  // if (to.path === '/driver/live-tracking') {
  //   // Start tracking when entering
  //   trackingStore.startTracking(auth.vehicle_id);
  // }

  // if (from.path === '/driver/live-tracking') {
  //   // Stop tracking when leaving
  //   trackingStore.stopTracking();
  // }

  //auth logic
  if (to.meta.requiresAuth) {
   
    if (!auth.user && auth.token) await auth.fetchUser()
  
    const isLoggedIn = !!auth.token
    const userRoles = auth.user?.roles?.map(role => role.name) || []

    if (to.meta.requiresAuth && !isLoggedIn) {
      return next({ name: 'login' })
    }
    if (isLoggedIn && to.meta.roles && !to.meta.roles.some(role => userRoles.includes(role))) {
      return next({ name: 'home' }) // or redirect to unauthorized page
    }
    
    if (to.meta.guest && isLoggedIn) {
      return next({ name: 'home' })
    }
  }

  next()
})

export default router