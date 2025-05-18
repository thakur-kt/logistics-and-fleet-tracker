import AdminView from '../views/AdminView.vue';
import UserRoleManager from '../components/admin/UserRoleManager.vue';
import PermissionManager from '../components/admin/PermissionManager.vue';
import RolePermissionManager from '../components/admin/RolePermissionManager.vue';
import VehiclesView from '../components/admin/vehicles/Index.vue';
// import DriverView from '../views/DriverView.vue';
// import VehicleForm from '../components/admin/VehicleForm.vue'
// const routes = [
//   { path: '/admin', component: AdminView },
//   { path: '/dispatcher', component: DispatcherView },
//   { path: '/driver', component: DriverView },
//   {
//     path: '/vehicles/create',
//     name: 'VehicleCreate',
//     component: VehicleForm,
//   },
//   {
//     path: '/vehicles/:id/edit',
//     name: 'VehicleEdit',
//     component: VehicleForm,
//     props: route => ({ vehicleId: route.params.id }),
//   }
// ];

// const router = createRouter({
//   history: createWebHistory(),
//   routes,
// });

// export default router;
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import Home from '@/views/Home.vue'
import Register from '@/views/Register.vue'
import Login from '@/views/Login.vue'

const routes = [
  { path: '/', component: Home ,name:'home',meta: { requiresAuth: true, role: ['admin' ]}},
  { path: '/login', component: Login ,  name: 'login' ,beforeEnter: (to, from, next) => {
    const auth = useAuthStore()
    if (auth.user) {
      next({ name: 'home' })  // or wherever your home/dashboard is
    } else {
      next()
    }
  },},
  { path: '/register', component: Register,name: 'register', beforeEnter: (to, from, next) => {
    const auth = useAuthStore()
    if (auth.user) {
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
  // {
  //   path: '/dispatcher',
  //   name: 'dispatcher',
  //   component: () => import('@/views/DispatcherDashboard.vue'),
  //   meta: { requiresAuth: true, roles: ['dispatcher'] }
  // }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()

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