import { loadIcon } from '@/utils/lazyIcon'

export const navItems = [
  {
    label: 'Dashboard',
    to: '/',
    icon: loadIcon('Home'),
    roles: ['admin', 'user','driver']
  },
  {
    label: 'Users',
    icon: loadIcon('Users'),
    suffixIcon: loadIcon('ChevronRight'),
    roles: ['admin', 'user'],
    children: [
      // { label: 'All Users', to: '/users',roles: ['admin', 'user'] },
      { label: 'Roles Manager', to: '/roles-manager' ,roles: ['admin', 'user']},
      { label: 'Permission Manager', to: '/permission-manager',roles: ['admin', 'user'] },
      { label: 'Roles Permission Manager', to: '/roles-permission-manager',roles: ['admin', 'user'] },
    ],
  },
  {
    label: 'Vehicles',
    to: '/vehicles',
    icon: loadIcon('Truck'),
    roles: ['admin']
  },
  {
    label: 'Delivery Orders',
    to: '/delivery-orders',
    icon: loadIcon('Truck'),
    roles: ['admin', 'driver']
  },
  {
    label: 'Settings',
    to: '/settings',
    icon: loadIcon('Settings'),
    roles: ['admin', 'user']
  }
]
